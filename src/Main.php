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

class Main extends PluginBase implements Listener
{

	public const InvalidConfig = "NoticeRemoved in config.yml doesn't exist";
	public const HandleFont = TF::ESCAPE . "　";

	public $history;

	public function InvalidConfig() : void
	{
		$this->history->save();
		$NoticeRemoved = $this->getConfig()->get("NoticeRemoved", self::InvalidConfig);
		$this->getLogger()->info(TF::DARK_RED . $NoticeRemoved);
	}

	public function RemoveConfig() : void
	{
		foreach ($this->history->getAll() as $history => $data) {
			$this->history->remove($history);
		}
	}

	public function onEnable() : void
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
		$this->saveResource("history.yml");
		$this->history = new Config($this->getDataFolder()."history.yml", Config::YAML);
		if ($this->getConfig()->get("DeleteHistory")["onEnable"] == true) {
			$this->RemoveConfig();
			$this->InvalidConfig();
		}
        if (class_exists(InfoAPI::class)) {
            SenderInfo::init();
            CommandInfo::init();
            CommandExecutionContextInfo::init();
            UtilsInfo::init();
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
        $message = $this->getConfig()->get(
            "TrackMessage",
           "{Sender Name} > /{Label} {Arguments}"
        );
        $messageToPlayer = $this->getConfig()->get(
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
