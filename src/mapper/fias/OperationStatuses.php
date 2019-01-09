<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Статусы.
 */
class OperationStatuses extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'OPERSTATID' => 'uuid',
        'NAME' => 'string',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'OPERSTATID';
    /**
     * @var string
     */
    protected $xmlPath = '/OperationStatuses/OperationStatus';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_OPERSTAT_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_OPERSTAT_*.XML';
}
