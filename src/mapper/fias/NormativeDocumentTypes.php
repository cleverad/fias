<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Типы нормативных документов.
 */
class NormativeDocumentTypes extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'NDTYPEID' => 'uuid',
        'NAME' => 'string',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'NDTYPEID';
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
}
