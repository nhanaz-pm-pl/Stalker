<?php

declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\server\CommandEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use NhanAZ\Track\libs\JackMD\UpdateNotifier\UpdateNotifier;

class Main extends PluginBase implements Listener
{

    public CONST InvalidConfig = 'Invalid config. Please check config.yml again. Thank you.';
    
    public $history;

    public function onLoad() : void 
    {
        UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());
    }

    public function onEnable() : void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource('history.yml');
        $this->history = new Config($this->getDataFolder().'history.yml', Config::YAML);

        if ($this->getConfig()->get('DeleteHistory')['onEnable'] == true) {
            foreach ($this->history->getAll() as $history => $data) {
                $this->history->remove($history);
            }
            $this->history->save();
            $NoticeRemoved = $this->getConfig()->get('NoticeRemoved', self::InvalidConfig);
            $this->getLogger()->info($NoticeRemoved);
        } 
    }

    public function onDisable() : void
    {
        if ($this->getConfig()->get('DeleteHistory')['onDisable'] == true) {
            foreach ($this->history->getAll() as $history => $data) {
                $this->history->remove($history);
            }
            $this->history->save();
            $NoticeRemoved = $this->getConfig()->get('NoticeRemoved', self::InvalidConfig);
            $this->getLogger()->info($NoticeRemoved);
        }
    }

    public function onCommandEvent(CommandEvent $event)
    {
        $cmd = $event->getCommand();
        $time = date('D d/m/Y H:i:s(A)');
        $name = $event->getSender()->getName();
        $this->history->set("{$time} : {$name}", $cmd);
        $this->history->save();
        $this->getLogger()->info("{$name} > {$cmd}");
        $trackers = $this->getConfig()->get('Trackers');
        foreach ($trackers as $tracker) {
            $tracker = Main::getInstance()->getServer()->getPlayerByPrefix($tracker);
            if ($tracker) {
                (string) $prefix = $this->getDescription()->getPrefix();
                $UnicodeFont = $this->getConfig()->get('UnicodeFont');
                $Handle_Variable_UnicodeFont = ($UnicodeFont == true ? self::Handle_Font : '');
                $tracker->sendMessage("{$Handle_Variable_UnicodeFont} [{$prefix}] {$name} > {$cmd}");
            }
        }
        return true;
    }
}
