<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;
use marvin255\fias\mapper\field\IntNumber;

/**
 * Типы объектов в адресах.
 */
class AddressObjectTypes extends AbstractMapper
{
    /**
     * @var string
     */
    protected $xmlPath = '/AddressObjectTypes/AddressObjectType';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_SOCRBASE_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_SOCRBASE_*.XML';

    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'KOD_T_ST' => new IntNumber(4),
            'LEVEL' => new IntNumber(4),
            'SOCRNAME' => new Line,
            'SCNAME' => new Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['KOD_T_ST'];
    }
}
