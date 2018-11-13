<?php

declare(strict_types=1);

namespace marvin255\fias\mapper\fias;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;
use marvin255\fias\mapper\field\Date;

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
            'NORMDOCID' => new Line(36),
            'DOCNAME' => new Line,
            'DOCDATE' => new Date,
            'DOCNUM' => new Line,
            'DOCTYPE' => new Line,
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
