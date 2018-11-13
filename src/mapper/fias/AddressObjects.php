<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;
use marvin255\fias\mapper\field\IntNumber;
use marvin255\fias\mapper\field\Date;

/**
 * Адреса.
 */
class AddressObjects extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'AOID' => new Line(36),
            'AOGUID' => new Line(36),
            'PARENTGUID' => new Line(36),
            'NEXTID' => new Line(36),
            'FORMALNAME' => new Line,
            'OFFNAME' => new Line,
            'SHORTNAME' => new Line,
            'AOLEVEL' => new IntNumber,
            'REGIONCODE' => new Line(2),
            'AREACODE' => new Line(3),
            'AUTOCODE' => new Line(1),
            'CITYCODE' => new Line(3),
            'CTARCODE' => new Line(3),
            'PLACECODE' => new Line(3),
            'PLANCODE' => new Line(4),
            'STREETCODE' => new Line(4),
            'EXTRCODE' => new Line(4),
            'SEXTCODE' => new Line(3),
            'PLAINCODE' => new Line(15),
            'CURRSTATUS' => new IntNumber,
            'ACTSTATUS' => new IntNumber,
            'LIVESTATUS' => new IntNumber,
            'CENTSTATUS' => new IntNumber,
            'OPERSTATUS' => new IntNumber,
            'IFNSFL' => new Line(4),
            'IFNSUL' => new Line(4),
            'TERRIFNSFL' => new Line(4),
            'TERRIFNSUL' => new Line(4),
            'OKATO' => new Line(11),
            'OKTMO' => new Line(11),
            'POSTALCODE' => new Line(6),
            'STARTDATE' => new Date,
            'ENDDATE' => new Date,
            'UPDATEDATE' => new Date,
            'DIVTYPE' => new IntNumber,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getXmlPath(): string
    {
        return '/AddressObjects/Object';
    }

    /**
     * @inhertitdoc
     */
    public function getInsertFileMask(): string
    {
        return 'AS_ADDROBJ_*.XML';
    }

    /**
     * @inhertitdoc
     */
    public function getDeleteFileMask(): string
    {
        return 'AS_DEL_ADDROBJ_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['AOID'];
    }
}
