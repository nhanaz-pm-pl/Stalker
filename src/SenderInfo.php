<?php


declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use SOFe\InfoAPI\Info;
use SOFe\InfoAPI\InfoAPI;
use SOFe\InfoAPI\PlayerInfo;
use SOFe\InfoAPI\StringInfo;

if (!class_exists(Info::class)) {
    return;
}

final class SenderInfo extends Info
{

    public function __construct(
        protected CommandSender $value
    )
    {
    }

    public static function init() : void
    {
        InfoAPI::provideInfo(
            self::class,
            StringInfo::class,
            "Track.Sender.Name",
            fn(self $info) : StringInfo => new StringInfo(
                $info->getValue()->getName()
            )
        );
        InfoAPI::provideFallback(
            self::class,
            PlayerInfo::class,
            static function (self $info) : ?PlayerInfo {
                $value = $info->getValue();
                return $value instanceof Player
                    ? new PlayerInfo($value)
                    : null;
            }
        );
    }

    public function toString() : string
    {
        return $this->getValue()->getName();
    }

    /**
     * @return CommandSender
     */
    public function getValue() : CommandSender
    {
        return $this->value;
    }

}