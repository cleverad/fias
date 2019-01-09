<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Статусы.
 */
class EstateStatuses extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'ESTSTATID' => 'uuid',
        'NAME' => 'string',
    ];
    /**
     * @var string
     */
    protected $xmlPath = '/EstateStatuses/EstateStatus';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ESTSTAT_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ESTSTAT_*.XML';

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['ESTSTATID'];
    }
}
