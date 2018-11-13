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
    public function getXmlPath(): string
    {
        return '/AddressObjectTypes/AddressObjectType';
    }

    /**
     * @inhertitdoc
     */
    public function getInsertFileMask(): string
    {
        return 'AS_SOCRBASE_*.XML';
    }

    /**
     * @inhertitdoc
     */
    public function getDeleteFileMask(): string
    {
        return 'AS_DEL_SOCRBASE_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['KOD_T_ST'];
    }
}
