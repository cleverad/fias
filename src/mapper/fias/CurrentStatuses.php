<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Статусы.
 */
class CurrentStatuses extends AbstractMapper
{
    /**
     * @var string
     */
    protected $xmlPath = '/CurrentStatuses/CurrentStatus';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_CURENTST_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_CURENTST_*.XML';

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
    public function getSqlPrimary(): array
    {
        return ['CURENTSTID'];
    }
}
