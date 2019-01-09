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
     * @var string
     */
    protected $xmlPath = '/OperationStatuses/OperationStatus';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_OPERSTAT_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_OPERSTAT_*.XML';

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
    public function getSqlPrimary(): array
    {
        return ['OPERSTATID'];
    }
}
