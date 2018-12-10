<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Статусы.
 */
class OperationStatuses extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'OPERSTATID' => new Line(36),
            'NAME' => new Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getXmlPath(): string
    {
        return '/OperationStatuses/OperationStatus';
    }

    /**
     * @inheritdoc
     */
    public function getInsertFileMask(): string
    {
        return 'AS_OPERSTAT_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getDeleteFileMask(): string
    {
        return 'AS_DEL_OPERSTAT_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['OPERSTATID'];
    }
}
