<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Типы квартир.
 */
class FlatTypes extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'FLTYPEID' => new Line(36),
            'NAME' => new Line,
            'SHORTNAME' => new Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getXmlPath(): string
    {
        return '/FlatTypes/FlatType';
    }

    /**
     * @inheritdoc
     */
    public function getInsertFileMask(): string
    {
        return 'AS_FLATTYPE_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getDeleteFileMask(): string
    {
        return 'AS_DEL_FLATTYPE_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['FLTYPEID'];
    }
}
