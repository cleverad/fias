<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Статусы.
 */
class StructureStatuses extends AbstractMapper
{
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

    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'STRSTATID' => new Line(36),
            'NAME' => new Line,
            'SHORTNAME' => new Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['STRSTATID'];
    }
}
