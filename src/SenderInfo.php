<?php


declare(strict_types=1);

namespace NhanAZ\Track;

use pocketmine\command\CommandSender;
use SOFe\InfoAPI\Info;

final class SenderInfo extends Info
{

    public function __construct(
        protected CommandSender $value
    )
    {
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