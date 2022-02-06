<?php


declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\command\Command;
use SOFe\InfoAPI\Info;
use SOFe\InfoAPI\InfoAPI;
use SOFe\InfoAPI\StringInfo;
use function class_exists;

if (!class_exists(Info::class)) {
    return;
}

class CommandInfo extends Info
{

    public function __construct(
        protected Command $value,
    )
    {
    }

    public function toString() : string
    {
        return $this->getValue()->getName();
    }

    public static function init() : void
    {
        InfoAPI::provideInfo(
            self::class,
            StringInfo::class,
            "Track.Command.Name",
            fn(self $info) : StringInfo => new StringInfo(
                $info->getValue()->getName()
            )
        );
    }

    /**
     * @return Command
     */
    public function getValue() : Command
    {
        return $this->value;
    }

}