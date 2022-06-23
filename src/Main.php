<?php

declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\server\CommandEvent;

class Main extends PluginBase implements Listener {

	public $log;

	public function RemoveConfig(): void {
		foreach ($this->log->getAll() as $log => $data) {
			$this->log->remove($log);
		}
	}

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$this->saveResource("log.yml");
		$this->log = new Config($this->getDataFolder() . "log.yml", Config::YAML);
		if ($this->getConfig()->getNested("deleteLog.onEnable")) {
			$this->RemoveConfig();
		}
	}

	protected function onDisable(): void {
		if ($this->getConfig()->getNested("deleteLog.onDisable")) {
			$this->RemoveConfig();
		}
	}

	public function onCommandEvent(CommandEvent $event) {
		$cmd = $event->getCommand();

		$time = date("D d/m/Y H:i:s(A)");
		$name = $event->getSender()->getName();

		$this->log->set("{$time} : {$name}", $cmd);
		$this->log->save();

		$this->getLogger()->info("{$name} > /{$cmd}");

		foreach ($this->getServer()->getOnlinePlayers() as $tracker) {
			if ($tracker->hasPermission("track.tracker")) {
				$tracker->sendMessage("[Track] {$name} > /{$cmd}");
			}
		}
		return true;
	}
}
