<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Статусы.
 */
class CurrentStatus extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'CURENTSTID' => new Line(36),
            'NAME' => new Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getXmlPath(): string
    {
        return '/CenterStatuses/CenterStatus';
    }

    /**
     * @inhertitdoc
     */
    public function getInsertFileMask(): string
    {
        return 'AS_CURENTST_*.XML';
    }

    /**
     * @inhertitdoc
     */
    public function getDeleteFileMask(): string
    {
        return 'AS_DEL_CURENTST_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['CURENTSTID'];
    }
}
