<?php

declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\MainLogger;
use pocketmine\utils\Terminal;
use pocketmine\utils\Timezone;
use Webmozart\PathUtil\Path;

class Main extends PluginBase implements Listener {

	private MainLogger $logger;

	protected function onEnable(): void {
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->logger = new MainLogger(Path::join($this->getDataFolder(), "log.log"), Terminal::hasFormattingCodes(), "Server", new \DateTimeZone(Timezone::get()));
	}

	/**
	 * @handleCancelled true
	 */
	public function onCommandEvent(CommandEvent $event): bool {
		$cmd = $event->getCommand();
		$name = $event->getSender()->getName();

		$exceptionCmds = $this->getConfig()->get("exceptionCmds");
		if (is_array($exceptionCmds) && in_array(explode(" ", $cmd)[0], $exceptionCmds, true)) {
			$cmd = preg_replace('/[^\s]/', "*", $cmd);
		 }

		$replacements = ["{sender}" => $name, "{command}" => $cmd];
		$trackMsg = str_replace(array_keys($replacements), $replacements, strval($this->getConfig()->get("trackMessage")));

		$this->logger->info($trackMsg);

		$onlinePlayers = $this->getServer()->getOnlinePlayers();
		foreach ($onlinePlayers as $tracker) {
			if ($tracker->hasPermission("track.tracker")) {
				$tracker->sendMessage($trackMsg);
			}
		}
		return true;
	}
}
