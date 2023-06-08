<?php

declare(strict_types=1);

namespace NhanAZ\Stalker;

use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\MainLogger;
use pocketmine\utils\Terminal;
use pocketmine\utils\Timezone;
use Symfony\Component\Filesystem\Path;

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
		$message = str_replace(array_keys($replacements), $replacements, strval($this->getConfig()->get("message")));

		$this->logger->info($message);

		$onlinePlayers = $this->getServer()->getOnlinePlayers();
		foreach ($onlinePlayers as $stalker) {
			if ($stalker->hasPermission("stalker")) {
				$stalker->sendMessage($message);
			}
		}
		return true;
	}
}
