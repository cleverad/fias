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
     * @var string
     */
    protected $xmlPath = '/CenterStatuses/CenterStatus';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_CENTERST_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_CENTERST_*.XML';

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
    public function getSqlPrimary(): array
    {
        return ['CENTERSTID'];
    }
}
