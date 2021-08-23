<?php

declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\RemoteServerCommandEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

class Main extends PluginBase implements Listener
{

    CONST InvalidConfig = "Invalid config. Please check config.yml again. Thank you.";

    public function onEnable() : void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
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
        if ($cmd[0] == "/") {
        $this->getLogger()->info($name . ' > ' . $cmd);
        $trackers = $this->getConfig()->get('Trackers');
            foreach ($trackers as $tracker) {
                $tracker = $this->getServer()->getPlayer($tracker);
                if ($tracker) {
                    $prefix = $this->getDescription()->getPrefix();
                    $tracker->sendMessage('[' . $prefix . '] ' . $name . ' > ' . $cmd);
                    $time = date("D d/m/Y H:i:s(A)");
                    $this->history->set($time . ' : ' . $name, $cmd);
                    $this->history->save();
                }
            }
        }
        return true;

    }

    public function onServerCommand(ServerCommandEvent $event)
    {
        $cmd = $event->getCommand();
        $time = date("D d/m/Y H:i:s(A)");
        $this->history->set($time . ' : Console', $cmd);
        $this->history->save();
        $this->getLogger()->info('Console > ' . $cmd);
        $trackers = $this->getConfig()->get('Trackers');
        foreach ($trackers as $tracker) {
            $tracker = $this->getServer()->getPlayer($tracker);
            if ($tracker) {
                $tracker->sendMessage('Console > ' . $cmd);
            }
        }
        return true;

    }

    public function onRemoteCommand(RemoteServerCommandEvent $event)
    {
        $cmd = $event->getCommand();
        $time = date("D d/m/Y H:i:s(A)");
        $this->history->set($time . ' : Rcon', $cmd);
        $this->history->save();
        $this->getLogger()->info('Rcon > ' . $cmd);
        $trackers = $this->getConfig()->get('Trackers');
        foreach ($trackers as $tracker) {
            $tracker = $this->getServer()->getPlayer($tracker);
            if ($tracker) {
            $tracker->sendMessage('Rcon > ' . $cmd);
            }
        }
        return true;
        
    }

}
