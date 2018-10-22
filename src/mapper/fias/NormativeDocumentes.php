<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field;

/**
 * Нормативные документы.
 */
class NormativeDocumentes extends AbstractMapper
{
    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return [
            'NORMDOCID' => new field\Line(36),
            'DOCNAME' => new field\Line,
            'DOCDATE' => new field\Date,
            'DOCNUM' => new field\Line,
            'DOCTYPE' => new field\Line,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getXmlPath(): string
    {
        return '/NormativeDocumentes/NormativeDocument';
    }

    /**
     * @inhertitdoc
     */
    public function getInsertFileMask(): string
    {
        return 'AS_NORMDOC_*.XML';
    }

    /**
     * @inhertitdoc
     */
    public function getDeleteFileMask(): string
    {
        return 'AS_DEL_NORMDOC_*.XML';
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        return ['NORMDOCID'];
    }
}
