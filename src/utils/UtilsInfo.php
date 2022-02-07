<?php


declare(strict_types=1);

use SOFe\InfoAPI\Info;
use SOFe\InfoAPI\InfoAPI;
use SOFe\InfoAPI\StringInfo;

class UtilsInfo extends Info
{

    public static function init() : void
    {
        InfoAPI::provideInfo(
            self::class,
            StringInfo::class,
            "Track.Utils.UnicodeFont",
            fn(self $info) : StringInfo => new StringInfo(
                \NhanAZ\Track\Main::HandleFont
            )
        );
    }

    public function toString() : string
    {
        // TODO: Implement toString() method.
    }
}