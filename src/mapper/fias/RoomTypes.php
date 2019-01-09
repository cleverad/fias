<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Типы комнат.
 */
class RoomTypes extends AbstractMapper
{
    /**
     * @var string
     */
    protected $xmlPath = '/RoomTypes/RoomType';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_ROOMTYPE_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_ROOMTYPE_*.XML';

    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'RMTYPEID' => new Line(36),
            'NAME' => new Line,
            'SHORTNAME' => new Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['RMTYPEID'];
    }
}
