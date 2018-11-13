<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;
use marvin255\fias\mapper\field\Date;
use marvin255\fias\mapper\field\IntNumber;

/**
 * Комнаты.
 */
class Rooms extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'ROOMID' => new Line(36),
            'ROOMGUID' => new Line(36),
            'HOUSEGUID' => new Line(36),
            'REGIONCODE' => new Line(2),
            'FLATNUMBER' => new Line(50),
            'FLATTYPE' => new IntNumber(11),
            'POSTALCODE' => new Line(6),
            'STARTDATE' => new Date,
            'ENDDATE' => new Date,
            'UPDATEDATE' => new Date,
            'OPERSTATUS' => new Line,
            'LIVESTATUS' => new Line,
            'NORMDOC' => new Line(36),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getXmlPath(): string
    {
        return '/Rooms/Room';
    }

    /**
     * @inhertitdoc
     */
    public function getInsertFileMask(): string
    {
        return 'AS_ROOM_*.XML';
    }

    /**
     * @inhertitdoc
     */
    public function getDeleteFileMask(): string
    {
        return 'AS_DEL_ROOM_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['ROOMID'];
    }
}
