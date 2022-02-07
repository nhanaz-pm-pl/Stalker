<?php


declare(strict_types=1);

namespace NhanAZ\Track\utils;

use NhanAZ\Track\Main;
use RuntimeException;
use SOFe\InfoAPI\Info;
use SOFe\InfoAPI\InfoAPI;
use SOFe\InfoAPI\StringInfo;

if (!class_exists(Info::class)) {
    return;
}

class UtilsInfo extends Info
{

    public static function init() : void
    {
        InfoAPI::provideInfo(
            self::class,
            StringInfo::class,
            "Track.Utils.UnicodeFont",
            fn(self $info) : StringInfo => new StringInfo(
                Main::HandleFont
            )
        );
    }

    public function toString() : string
    {
        throw new RuntimeException(
            self::class . " must not be returned as a provided info"
        );
    }
}