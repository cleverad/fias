<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Статусы.
 */
class ActualStatuses extends AbstractMapper
{
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

    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'ACTSTATID' => new Line(36),
            'NAME' => new Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['ACTSTATID'];
    }
}
