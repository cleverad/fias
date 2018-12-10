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
    public function getXmlPath(): string
    {
        return '/ActualStatuses/ActualStatus';
    }

    /**
     * @inheritdoc
     */
    public function getInsertFileMask(): string
    {
        return 'AS_ACTSTAT_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getDeleteFileMask(): string
    {
        return 'AS_DEL_ACTSTAT_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['ACTSTATID'];
    }
}
