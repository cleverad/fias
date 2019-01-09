<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Статусы.
 */
class IntervalStatuses extends AbstractMapper
{
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

    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'INTVSTATID' => new Line(36),
            'NAME' => new Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['INTVSTATID'];
    }
}
