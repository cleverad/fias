<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Статусы.
 */
class CenterStatuses extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'CENTERSTID' => new Line(36),
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
     * @inheritdoc
     */
    public function getInsertFileMask(): string
    {
        return 'AS_CENTERST_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getDeleteFileMask(): string
    {
        return 'AS_DEL_CENTERST_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['CENTERSTID'];
    }
}
