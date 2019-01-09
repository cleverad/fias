<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Типы квартир.
 */
class FlatTypes extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'FLTYPEID' => 'int',
        'NAME' => 'string',
        'SHORTNAME' => 'string',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'FLTYPEID';
    /**
     * @var string
     */
    protected $xmlPath = '/FlatTypes/FlatType';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_FLATTYPE_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_FLATTYPE_*.XML';
}
