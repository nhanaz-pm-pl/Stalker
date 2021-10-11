<?php

declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\event\server\CommandEvent;
use NhanAZ\Track\libs\JackMD\UpdateNotifier\UpdateNotifier;

class Main extends PluginBase implements Listener
{

    public CONST InvalidConfig = TextFormat::DARK_RED . "NoticeRemoved in config.yml doesn't exist";
    public CONST HandleFont = TextFormat::ESCAPE . "　";

    public $history;

    public function onLoad() : void
    {
        $description = $this->getDescription()->getName();
        $version =  $this->getDescription()->getVersion();
        UpdateNotifier::checkUpdate($description, $version);
    }

    public function InvalidConfig() : void
    {
        $this->history->save();
        $NoticeRemoved = $this->getConfig()->get("NoticeRemoved", self::InvalidConfig);
        $this->getLogger()->info($NoticeRemoved);
    }

    public function RemoveConfig() : void
    {
        foreach ($this->history->getAll() as $history => $data) {
            $this->history->remove($history);
        }
    }

    public function onEnable() : void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("history.yml");
        $this->history = new Config($this->getDataFolder()."history.yml", Config::YAML);
        if ($this->getConfig()->get("DeleteHistory")["onEnable"] == true) {
            $this->RemoveConfig();
            $this->InvalidConfig();
        }
    }

    public function onDisable() : void
    {
        if ($this->getConfig()->get("DeleteHistory")["onDisable"] == true) {
            $this->RemoveConfig();
            $this->InvalidConfig();
        }
    }

    public function onCommandEvent(CommandEvent $event)
    {
        $cmd = $event->getCommand();
        $time = date("D d/m/Y H:i:s(A)");
        $name = $event->getSender()->getName();
        $this->history->set("{$time} : {$name}", $cmd);
        $this->history->save();
        $this->getLogger()->info("{$name} > /{$cmd}");
        $trackers = $this->getConfig()->get("Trackers");
        foreach ($trackers as $tracker) {
            $tracker = $this->getServer()->getPlayerByPrefix($tracker);
            if ($tracker) {
                (string) $prefix = $this->getDescription()->getPrefix();
                $UnicodeFont = $this->getConfig()->get("UnicodeFont");
                $Handle_Variable_UnicodeFont = ($UnicodeFont == true ? self::HandleFont : "");
                $tracker->sendMessage("{$Handle_Variable_UnicodeFont}[{$prefix}] {$name} > /{$cmd}");
            }
        }
        return true;
    }
}
