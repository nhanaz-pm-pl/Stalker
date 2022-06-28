<?php

declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\server\CommandEvent;

class Main extends PluginBase implements Listener {

	private string $logPath;

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->initConfig();
		$this->initLog();
		$this->deleteLog();
	}

	private function initLog(): void {
		$this->logPath = $this->getDataFolder() . "log.log";
		if (!file_exists($this->logPath)) $this->createLogFile();
	}

	private function createLogFile() {
		file_put_contents($this->logPath, "");
	}

	private function initConfig(): void {
		$this->saveDefaultConfig();
	}

	private function deleteLog(): void {
		if ($this->getConfig()->getNested("deleteLog.onEnable", false)) {
			$this->createLogFile();
		}
		if ($this->getConfig()->getNested("deleteLog.onDisable", false)) {
			$this->createLogFile();
		}
	}

	protected function onDisable(): void {
		$this->deleteLog();
	}

	private function onLog($time, $sender, $command): void {
		if (filesize($this->logPath) / 1048576 >= $this->getConfig()->get("maxSize", 16)) {
			$this->createLogFile();
		}
		$replacements = [
			"{time}" => $time,
			"{sender}" => $sender,
			"{command}" => $command
		];
		$message = str_replace(
			array_keys($replacements),
			$replacements,
			$this->getConfig()->get("logFormat", "{time} [{sender}]: /{command}")
		) . PHP_EOL;
		file_put_contents($this->logPath, $message, FILE_APPEND);
		clearstatcache(true, $this->logPath);
	}

	public function onCommandEvent(CommandEvent $event) {
		$cmd = $event->getCommand();
		$time = date($this->getConfig()->get("timeFormat", "Y-m-d [H:i:s]"));
		$name = $event->getSender()->getName();
		$exceptionCmds = $this->getConfig()->get("exceptionCmds", []);
		foreach ($exceptionCmds as $exceptionCmd) {
			$cmdArr = explode(" ", $cmd);
			if ($cmdArr[0] === $exceptionCmd) {
				$cmd = preg_replace('/[^\s]/', "*", $cmd);
			}
		}
		$this->onLog($time, $name, $cmd);
		$replacements = [
			"{sender}" => $name,
			"{command}" => $cmd
		];
		$trackMsg = str_replace(
			array_keys($replacements),
			$replacements,
			$this->getConfig()->get("trackMessage", "<{sender}> /{command}")
		);
		$this->getLogger()->info($trackMsg);
		foreach ($this->getServer()->getOnlinePlayers() as $tracker) {
			if ($tracker->hasPermission("track.tracker")) {
				$tracker->sendMessage($trackMsg);
			}
		}
		return true;
	}
}
