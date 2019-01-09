<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Статусы.
 */
class EstateStatuses extends AbstractMapper
{
    /**
     * @var string
     */
    protected $xmlPath = '/EstateStatuses/EstateStatus';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ESTSTAT_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ESTSTAT_*.XML';

    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'ESTSTATID' => new Line(36),
            'NAME' => new Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['ESTSTATID'];
    }
}
