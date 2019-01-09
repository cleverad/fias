<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Статусы.
 */
class IntervalStatuses extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'INTVSTATID' => 'uuid',
        'NAME' => 'string',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'INTVSTATID';
    /**
     * @var string
     */
    protected $xmlPath = '/IntervalStatuses/IntervalStatus';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_INTVSTAT_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_INTVSTAT_*.XML';
}
