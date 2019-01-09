<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Типы нормативных документов.
 */
class NormativeDocumentTypes extends AbstractMapper
{
    /**
     * @var string
     */
    protected $xmlPath = '/NormativeDocumentTypes/NormativeDocumentType';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_NDOCTYPE_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_NDOCTYPE_*.XML';

    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'NDTYPEID' => new Line(36),
            'NAME' => new Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['NDTYPEID'];
    }
}
