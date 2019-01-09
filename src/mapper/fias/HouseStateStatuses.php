<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Статусы.
 */
class HouseStateStatuses extends AbstractMapper
{
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
    protected function createFields(): array
    {
        return [
            'HOUSESTID' => new Line(36),
            'NAME' => new Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['HOUSESTID'];
    }
}
