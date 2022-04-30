<?php

declare(strict_types=1);

namespace NhanAZ\Track;

use NhanAZ\Track\utils\UtilsInfo;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use SOFe\InfoAPI\InfoAPI;
use SOFe\InfoAPI\TimeInfo;
use function class_exists;
use function explode;
use function ltrim;
use function microtime;
use function strlen;
use function strpos;
use function substr;

class Main extends PluginBase implements Listener {

	public const HandleFont = TF::ESCAPE . "　";

	protected Config $cfg;

	private string $logPath;

	public function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->initConfig();
		$this->cfg = $this->getConfig();
		$this->initLog();
		$this->initInfoAPI();
		$this->deleteHistory();
	}

	private function initInfoAPI(): void {
		if (class_exists(InfoAPI::class)) {
			SenderInfo::init();
			CommandInfo::init();
			CommandExecutionContextInfo::init();
			UtilsInfo::init();
		}
	}

	private function initLog(): void {
		$this->logPath = $this->getDataFolder() . "history.log";
		if (!file_exists($this->logPath)) $this->createLogFile();
	}

	private function initConfig(): void {
		$this->saveDefaultConfig();
	}

	private function createLogFile() {
		file_put_contents($this->logPath, "# [Time] {Sender} > /{Command}\n");
	}

	private function deleteHistory(): void {
		if ($this->cfg->getNested("DeleteHistory.onEnable", false)) {
			$this->createLogFile();
		}
		if ($this->cfg->getNested("DeleteHistory.onDisable", false)) {
			$this->createLogFile();
		}
	}

	protected function onDisable(): void {
		$this->deleteHistory();
	}

	private function onLog($time, $sender, $command): void {
		if (filesize($this->logPath) / 1048576 >= $this->cfg->get("MaxSize", 16)) {
			$this->createLogFile();
		}
		$message = "# [" . $time . "] {" . $sender . "} > /" . $command . "\n";
		file_put_contents($this->logPath, $message, FILE_APPEND);
		clearstatcache(true, $this->logPath);
	}

	public function onCommandEvent(CommandEvent $event) {
		$cmd = $event->getCommand();

		$time = date("D d/m/Y H:i:s(A)");
		$name = $event->getSender()->getName();

		$this->onLog($time, $name, $cmd);

		[
			$time,
			$microTime
		] = explode(".", microtime());
		$commandTrim = ltrim($cmd);
		$commandInstance = $this->getServer()->getCommandMap()->getCommand(
			substr(
				$commandTrim,
				0,
				($commandFirstSpace = strpos($commandTrim, " "))
					!== false
					? $commandFirstSpace
					: strlen($commandTrim)
			)
		);
		$message = $this->cfg->get(
			"TrackMessage",
			"{Sender Name} > /{Label} {Arguments}"
		);
		$messageToPlayer = $this->cfg->get(
			"TrackMessageToPlayer",
			"{UnicodeFont}{DARKGRAY}[Track] {GRAY}{Sender Name} > /{Label} {Arguments}"
		) ?? $message;

		if (class_exists(InfoAPI::class)) {
			$context = new CommandExecutionContextInfo(
				new SenderInfo($event->getSender()),
				new TimeInfo((int)$time, (int)$microTime),
				$commandInstance === null
					? null
					: new CommandInfo($commandInstance),
				$cmd,
				$commandFirstSpace !== false
					? [substr($commandTrim, $commandFirstSpace + 1)]
					: []
				// Making this argument array is just for backward compatibility.
			);
			$message = InfoAPI::resolve(
				(string)$message,
				$context
			);
			$messageToPlayer = InfoAPI::resolve(
				(string)$messageToPlayer,
				$context
			);
		} else {
			$message = $message === ""
				? $message
				: "$name > /$cmd";
			$messageToPlayer = $messageToPlayer === ""
				? $messageToPlayer
				: self::HandleFont . "[Track] $name > /$cmd";
		}
		if ($message !== "") {
			$this->getLogger()->info($message);
		}
		if ($messageToPlayer !== "") {
			foreach ($this->getServer()->getOnlinePlayers() as $tracker) {
				if ($tracker->hasPermission("track.tracker")) {
					$tracker->sendMessage($messageToPlayer);
				}
			}
		}

		return true;
	}
}
