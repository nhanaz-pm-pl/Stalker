<?php

declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\event\server\CommandEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use NhanAZ\Track\libs\JackMD\UpdateNotifier\UpdateNotifier;

class Main extends PluginBase implements Listener
{

	public CONST InvalidConfig = "Invalid config! Please check config.yml again!";

	public CONST Handle_Font = TextFormat::ESCAPE . "　";

	public $history;

	public function onLoad() : void
	{
		UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());
	}

	public function onEnable() : void
	{
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("history.yml");
		$this->history = new Config($this->getDataFolder()."history.yml", Config::YAML);

		if ($this->getConfig()->get("DeleteHistory")["onEnable"] == true) {
			foreach ($this->history->getAll() as $history => $data) {
				$this->history->remove($history);
			}
			$this->history->save();
			$NoticeRemoved = $this->getConfig()->get("NoticeRemoved", self::InvalidConfig);
			$this->getLogger()->info($NoticeRemoved);
		}

	}

	public function onDisable() : void
	{
		if ($this->getConfig()->get("DeleteHistory")["onDisable"] == true) {
			foreach ($this->history->getAll() as $history => $data) {
				$this->history->remove($history);
			}
			$this->history->save();
			$NoticeRemoved = $this->getConfig()->get("NoticeRemoved", self::InvalidConfig);
			$this->getLogger()->info($NoticeRemoved);
		}

	}

	public function onCommandPreProcess(PlayerCommandPreprocessEvent $event)
	{
		$name = $event->getPlayer()->getName();
		$cmd = $event->getMessage();
		if ($cmd[0] === "/") {
			$this->track($name, substr($cmd, 1));
		}
		return true;

	}

	public function onCommandEvent(CommandEvent $event)
	{
		$cmd = $event->getCommand();
		$this->track($event->getSender()->getName(), $cmd);
		return true;

	}
	
	public function track(string $sender, string $cmd) : void {
		$time = date("D d/m/Y H:i:s(A)");
		$this->history->set($time . " : " . $sender . "," . $cmd);
		$this->history->save();
		$UnicodeFont = $this->getConfig()->get("UnicodeFont");
		$Handle_Variable_UnicodeFont = ($UnicodeFont == true ? self::Handle_Font : "");
		$this->getLogger()->info("[Track] " . $sender . " > " . $cmd . $Handle_Variable_UnicodeFont);
		foreach ($this->getServer()->getOnlinePlayers() as $tracker) {
			if ($tracker->hasPermission("track.tracker")) {
				$tracker->sendMessage($sender . " > " . $cmd);
			}
		}
	}
}
