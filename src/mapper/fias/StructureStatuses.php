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
    public function getXmlPath(): string
    {
        return '/StructureStatuses/StructureStatus';
    }

    /**
     * @inheritdoc
     */
    public function getInsertFileMask(): string
    {
        return 'AS_STRSTAT_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getDeleteFileMask(): string
    {
        return 'AS_DEL_STRSTAT_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['STRSTATID'];
    }
}
