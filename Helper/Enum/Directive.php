<?php

namespace Kaelyx\ConfigurableCSP\Helper\Enum;

class Directive
{
    public const BASE_URI = 'base-uri';
    public const CONNECT_SRC = 'connect-src';
    public const DEFAULT_SRC = 'default-src';
    public const FONT_SRC = 'font-src';
    public const FORM_ACTION = 'form-action';
    public const FRAME_ANCESTORS = 'frame-ancestors';
    public const FRAME_SRC = 'frame-src';
    public const IMG_SRC = 'img-src';
    public const MANIFEST_SRC = 'manifest-src';
    public const MEDIA_SRC = 'media-src';
    public const OBJECT_SRC = 'object-src';
    public const REPORT_TO = 'report-to';
    public const REPORT_URI = 'report-uri';
    public const SCRIPT_SRC = 'script-src';
    public const SCRIPT_SRC_ATTR = 'script-src-attr';
    public const STYLE_SRC = 'style-src';

    public static function getDirectives(): array
    {
        return [
            self::BASE_URI,
            self::CONNECT_SRC,
            self::DEFAULT_SRC,
            self::FONT_SRC,
            self::FORM_ACTION,
            self::FRAME_ANCESTORS,
            self::FRAME_SRC,
            self::IMG_SRC,
            self::MANIFEST_SRC,
            self::MEDIA_SRC,
            self::OBJECT_SRC,
            self::REPORT_TO,
            self::REPORT_URI,
            self::SCRIPT_SRC,
            self::SCRIPT_SRC_ATTR,
            self::STYLE_SRC
        ];  
    }
}