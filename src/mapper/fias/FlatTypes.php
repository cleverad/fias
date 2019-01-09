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
     * @var string
     */
    protected $xmlPath = '/FlatTypes/FlatType';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_FLATTYPE_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_FLATTYPE_*.XML';

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
    public function getSqlPrimary(): array
    {
        return ['FLTYPEID'];
    }
}
