<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Земельные участки.
 */
class Steads extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'STEADGUID' => 'uuid',
        'NUMBER' => 'string',
        'REGIONCODE' => ['string', 2],
        'POSTALCODE' => ['string', 6],
        'IFNSFL' => ['string', 4],
        'IFNSUL' => ['string', 4],
        'OKATO' => ['string', 11],
        'OKTMO' => ['string', 11],
        'PARENTGUID' => 'uuid',
        'STEADID' => 'uuid',
        'OPERSTATUS' => 'string',
        'STARTDATE' => 'date',
        'ENDDATE' => 'date',
        'UPDATEDATE' => 'date',
        'LIVESTATUS' => 'string',
        'DIVTYPE' => 'string',
        'NORMDOC' => 'uuid',
    ];
    /**
     * @var string
     */
    protected $xmlPath = '/Steads/Stead';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_STEAD_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_STEAD_*.XML';

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['STEADGUID'];
    }
}
