<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;
use marvin255\fias\mapper\field\Date;

/**
 * Земельные участки.
 */
class Steads extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'STEADGUID' => new Line(36),
            'NUMBER' => new Line,
            'REGIONCODE' => new Line(2),
            'POSTALCODE' => new Line(6),
            'IFNSFL' => new Line(4),
            'IFNSUL' => new Line(4),
            'OKATO' => new Line(11),
            'OKTMO' => new Line(11),
            'PARENTGUID' => new Line(36),
            'STEADID' => new Line(36),
            'OPERSTATUS' => new Line,
            'STARTDATE' => new Date,
            'ENDDATE' => new Date,
            'UPDATEDATE' => new Date,
            'LIVESTATUS' => new Line,
            'DIVTYPE' => new Line,
            'NORMDOC' => new Line(36),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getXmlPath(): string
    {
        return '/Steads/Stead';
    }

    /**
     * @inheritdoc
     */
    public function getInsertFileMask(): string
    {
        return 'AS_STEAD_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getDeleteFileMask(): string
    {
        return 'AS_DEL_STEAD_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['STEADGUID'];
    }
}
