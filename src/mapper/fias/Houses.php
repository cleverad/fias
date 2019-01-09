<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;
use marvin255\fias\mapper\field\Date;
use marvin255\fias\mapper\field\IntNumber;

/**
 * Дома.
 */
class Houses extends AbstractMapper
{
    /**
     * @var string
     */
    protected $xmlPath = '/Houses/House';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_HOUSE_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_HOUSE_*.XML';

    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'HOUSEID' => new Line(36),
            'HOUSEGUID' => new Line(36),
            'AOGUID' => new Line(36),
            'HOUSENUM' => new Line(20),
            'STRSTATUS' => new IntNumber(11),
            'ESTSTATUS' => new IntNumber(11),
            'STATSTATUS' => new IntNumber(11),
            'IFNSFL' => new Line(4),
            'IFNSUL' => new Line(4),
            'OKATO' => new Line(11),
            'OKTMO' => new Line(11),
            'POSTALCODE' => new Line(11),
            'STARTDATE' => new Date,
            'ENDDATE' => new Date,
            'UPDATEDATE' => new Date,
            'COUNTER' => new IntNumber(11),
            'DIVTYPE' => new IntNumber(11),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['HOUSEID'];
    }
}
