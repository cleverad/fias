<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Комнаты.
 */
class Rooms extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'ROOMID' => 'uuid',
        'ROOMGUID' => 'uuid',
        'HOUSEGUID' => 'uuid',
        'REGIONCODE' => ['string', 2],
        'FLATNUMBER' => ['string', 50],
        'FLATTYPE' => 'int',
        'POSTALCODE' => ['string', 6],
        'STARTDATE' => 'date',
        'ENDDATE' => 'date',
        'UPDATEDATE' => 'date',
        'OPERSTATUS' => 'string',
        'LIVESTATUS' => 'string',
        'NORMDOC' => 'uuid',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'ROOMID';
    /**
     * @var int
     */
    protected $sqlPartitionsCount = 4;
    /**
     * @var string
     */
    protected $sqlPartitionField = 'ROOMID';
    /**
     * @var string
     */
    protected $xmlPath = '/Rooms/Room';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ROOM_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ROOM_*.XML';
}
