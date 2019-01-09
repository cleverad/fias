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
        'DOCNAME' => 'string',
        'DOCDATE' => 'date',
        'DOCNUM' => 'string',
        'DOCTYPE' => 'string',
    ];
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

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['NORMDOCID'];
    }
}
