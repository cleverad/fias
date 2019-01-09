<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Типы объектов в адресах.
 */
class AddressObjectTypes extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'KOD_T_ST' => ['int', 4],
        'LEVEL' => ['int', 4],
        'SOCRNAME' => 'string',
        'SCNAME' => 'string',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'KOD_T_ST';
    /**
     * @var string
     */
    protected $xmlPath = '/AddressObjectTypes/AddressObjectType';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_SOCRBASE_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_SOCRBASE_*.XML';
}
