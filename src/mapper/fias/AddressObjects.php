<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Адреса.
 */
class AddressObjects extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'AOID' => 'uuid',
        'AOGUID' => 'uuid',
        'PARENTGUID' => 'uuid',
        'NEXTID' => 'uuid',
        'FORMALNAME' => 'string',
        'OFFNAME' => 'string',
        'SHORTNAME' => 'string',
        'AOLEVEL' => 'int',
        'REGIONCODE' => ['string', 2],
        'AREACODE' => ['string', 3],
        'AUTOCODE' => ['string', 1],
        'CITYCODE' => ['string', 3],
        'CTARCODE' => ['string', 3],
        'PLACECODE' => ['string', 4],
        'PLANCODE' => ['string', 4],
        'STREETCODE' => ['string', 4],
        'EXTRCODE' => ['string', 4],
        'SEXTCODE' => ['string', 3],
        'PLAINCODE' => ['string', 15],
        'CURRSTATUS' => 'int',
        'ACTSTATUS' => 'int',
        'LIVESTATUS' => 'int',
        'CENTSTATUS' => 'int',
        'OPERSTATUS' => 'int',
        'IFNSFL' => ['string', 4],
        'IFNSUL' => ['string', 4],
        'TERRIFNSFL' => ['string', 4],
        'TERRIFNSUL' => ['string', 4],
        'OKATO' => ['string', 11],
        'OKTMO' => ['string', 11],
        'POSTALCODE' => ['string', 6],
        'STARTDATE' => 'date',
        'ENDDATE' => 'date',
        'UPDATEDATE' => 'date',
        'DIVTYPE' => 'int',
    ];
    /**
     * @var string
     */
    protected $xmlPath = '/AddressObjects/Object';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ADDROBJ_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ADDROBJ_*.XML';

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['AOID'];
    }
}
