<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Статусы.
 */
class CurrentStatuses extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'CURENTSTID' => 'uuid',
        'NAME' => 'string',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'CURENTSTID';
    /**
     * @var string
     */
    protected $xmlPath = '/CurrentStatuses/CurrentStatus';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_CURENTST_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_CURENTST_*.XML';
}
