<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Статусы.
 */
class ActualStatuses extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'ACTSTATID' => 'uuid',
        'NAME' => 'string',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'ACTSTATID';
    /**
     * @var string
     */
    protected $xmlPath = '/ActualStatuses/ActualStatus';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ACTSTAT_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ACTSTAT_*.XML';
}
