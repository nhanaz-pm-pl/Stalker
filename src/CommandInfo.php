<?php


declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\command\Command;
use SOFe\InfoAPI\Info;

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

    }

    /**
     * @return Command
     */
    public function getValue() : Command
    {
        return $this->value;
    }

}