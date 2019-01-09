<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Статусы.
 */
class StructureStatuses extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'STRSTATID' => 'uuid',
        'NAME' => 'string',
        'SHORTNAME' => 'string',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'STRSTATID';
    /**
     * @var string
     */
    protected $xmlPath = '/StructureStatuses/StructureStatus';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_STRSTAT_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_STRSTAT_*.XML';
}
