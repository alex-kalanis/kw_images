<?php

namespace kalanis\kw_images;


/**
 * Class ImagesException
 * @package kalanis\kw_images
 * When something fails in images...
 */
class ImagesException extends \Exception
{
    public const FORMAT_FACTORY_WRONG_FORMAT = 100001;
    public const FORMAT_FACTORY_WRONG_TYPE = 100002;

    public const FORMAT_NO_LIBRARY = 100010;
    public const FORMAT_PNG_CANNOT_LOAD = 100011;
    public const FORMAT_PNG_CANNOT_SAVE = 100012;
    public const FORMAT_GIF_CANNOT_LOAD = 100013;
    public const FORMAT_GIF_CANNOT_SAVE = 100014;
    public const FORMAT_JPEG_CANNOT_LOAD = 100015;
    public const FORMAT_JPEG_CANNOT_SAVE = 100016;
    public const FORMAT_BMP_CANNOT_LOAD = 100017;
    public const FORMAT_BMP_CANNOT_SAVE = 100018;
    public const FORMAT_XBM_CANNOT_LOAD = 100019;
    public const FORMAT_XBM_CANNOT_SAVE = 100020;
    public const FORMAT_WBMP_CANNOT_LOAD = 100021;
    public const FORMAT_WBMP_CANNOT_SAVE = 100022;
    public const FORMAT_WEBP_CANNOT_LOAD = 100023;
    public const FORMAT_WEBP_CANNOT_SAVE = 100024;
    public const FORMAT_AVIF_CANNOT_LOAD = 100025;
    public const FORMAT_AVIF_CANNOT_SAVE = 100026;

    public const FORMAT_AUTO_NO_FILE = 100050;
    public const FORMAT_AUTO_NO_IMAGE = 100051;
    public const FORMAT_AUTO_CANNOT_SAVE = 100052;

    public const PROCESSOR_NO_LIBRARY = 100100;
    public const PROCESSOR_CANNOT_RESIZE = 100101;
    public const PROCESSOR_CANNOT_RESAMPLE = 100102;
    public const PROCESSOR_CANNOT_ROTATE = 100103;
    public const PROCESSOR_CANNOT_CREATE = 100104;
    public const PROCESSOR_CANNOT_GET_SIZE = 100105;
    public const PROCESSOR_CANNOT_GET_RESOURCE = 100106;
    public const PROCESSOR_CANNOT_CLEANUP = 100107;
    public const PROCESSOR_CANNOT_FLIP = 100108;

    public const TRAIT_WRONG_MIME = 900001;
    public const TRAIT_UNKNOWN_MIME = 900002;
}
