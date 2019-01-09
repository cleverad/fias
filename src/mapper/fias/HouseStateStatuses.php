<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Статусы.
 */
class HouseStateStatuses extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'HOUSESTID' => 'uuid',
        'NAME' => 'string',
    ];
    /**
     * @var string
     */
    protected $xmlPath = '/HouseStateStatuses/HouseStateStatus';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_HSTSTAT_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_HSTSTAT_*.XML';

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['HOUSESTID'];
    }
}
