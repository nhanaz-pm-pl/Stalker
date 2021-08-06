<?php

declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\event\server\RemoteServerCommandEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;


class Main extends PluginBase implements Listener
{

    public function onEnable() : void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
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
                }
            }
        }
        return true;
    }

    public function onServerCommand(ServerCommandEvent $event)
    {
        $cmd = $event->getCommand();
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
