<?php


declare(strict_types=1);

namespace NhanAZ\Track;

use SOFe\InfoAPI\Info;
use SOFe\InfoAPI\TimeInfo;

class CommandExecutionContextInfo extends Info
{

    public function __construct(
        protected SenderInfo  $sender,
        protected TimeInfo    $time,
        protected CommandInfo $command,
        protected string      $label,
        protected array       $arguments
    )
    {
    }

    public static function init() : void
    {

    }

    public function toString() : string
    {
        // TODO: Implement toString() method.
    }

    /**
     * @return SenderInfo
     */
    public function getSender() : SenderInfo
    {
        return $this->sender;
    }

    /**
     * @return TimeInfo
     */
    public function getTime() : TimeInfo
    {
        return $this->time;
    }

    /**
     * @return CommandInfo
     */
    public function getCommand() : CommandInfo
    {
        return $this->command;
    }

    /**
     * @return string
     */
    public function getLabel() : string
    {
        return $this->label;
    }

    /**
     * @return array
     */
    public function getArguments() : array
    {
        return $this->arguments;
    }

}