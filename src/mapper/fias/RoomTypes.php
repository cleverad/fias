<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Типы комнат.
 */
class RoomTypes extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'RMTYPEID' => 'int',
        'NAME' => 'string',
        'SHORTNAME' => 'string',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'RMTYPEID';
    /**
     * @var string
     */
    protected $xmlPath = '/RoomTypes/RoomType';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ROOMTYPE_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ROOMTYPE_*.XML';
}
