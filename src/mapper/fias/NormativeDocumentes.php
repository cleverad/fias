<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;

/**
 * Нормативные документы.
 */
class NormativeDocumentes extends AbstractMapper
{
    /**
     * @var mixed[]
     */
    protected $fields = [
        'NORMDOCID' => 'uuid',
        'DOCNAME' => ['string', 500],
        'DOCDATE' => 'date',
        'DOCNUM' => 'string',
        'DOCTYPE' => 'string',
    ];
    /**
     * @var string[]|string
     */
    protected $sqlPrimary = 'NORMDOCID';
    /**
     * @var string
     */
    protected $xmlPath = '/NormativeDocumentes/NormativeDocument';
    /**
     * @var string
     */
    protected $insertFileMask = 'AS_NORMDOC_*.XML';
    /**
     * @var string
     */
    protected $deleteFileMask = 'AS_DEL_NORMDOC_*.XML';
}
