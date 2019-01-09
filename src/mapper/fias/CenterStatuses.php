<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Статусы.
 */
class CenterStatuses extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'CENTERSTID' => 'uuid',
        'NAME' => 'string',
    ];
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
    public function getSqlPrimary(): array
    {
        return ['CENTERSTID'];
    }
}
