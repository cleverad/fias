<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field;

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
            'AOID' => new field\Line(36),
            'AOGUID' => new field\Line(36),
            'PARENTGUID' => new field\Line(36),
            'NEXTID' => new field\Line(36),
            'FORMALNAME' => new field\Line,
            'OFFNAME' => new field\Line,
            'SHORTNAME' => new field\Line,
            'AOLEVEL' => new field\IntNumber,
            'REGIONCODE' => new field\Line(2),
            'AREACODE' => new field\Line(3),
            'AUTOCODE' => new field\Line(1),
            'CITYCODE' => new field\Line(3),
            'CTARCODE' => new field\Line(3),
            'PLACECODE' => new field\Line(3),
            'PLANCODE' => new field\Line(4),
            'STREETCODE' => new field\Line(4),
            'EXTRCODE' => new field\Line(4),
            'SEXTCODE' => new field\Line(3),
            'PLAINCODE' => new field\Line(15),
            'CURRSTATUS' => new field\IntNumber,
            'ACTSTATUS' => new field\IntNumber,
            'LIVESTATUS' => new field\IntNumber,
            'CENTSTATUS' => new field\IntNumber,
            'OPERSTATUS' => new field\IntNumber,
            'IFNSFL' => new field\Line(4),
            'IFNSUL' => new field\Line(4),
            'TERRIFNSFL' => new field\Line(4),
            'TERRIFNSUL' => new field\Line(4),
            'OKATO' => new field\Line(11),
            'OKTMO' => new field\Line(11),
            'POSTALCODE' => new field\Line(6),
            'STARTDATE' => new field\Date,
            'ENDDATE' => new field\Date,
            'UPDATEDATE' => new field\Date,
            'DIVTYPE' => new field\IntNumber,
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
