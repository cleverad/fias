<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\fias\NormativeDocument;
use marvin255\fias\mapper\MapperInterface;
use DateTime;

/**
 * Тест маппера NormativeDocument.
 */
class NormativeDocumentTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getXmlTestData(): array
    {
        $data = [
            'NORMDOCID' => $this->faker()->unique()->uuid,
            'DOCNAME' => $this->faker()->unique()->text,
            'DOCDATE' => new DateTime($this->faker()->unique()->date),
            'DOCNUM' => $this->faker()->unique()->text,
            'DOCTYPE' => $this->faker()->unique()->uuid,
        ];

        $xml = '<NormativeDocument';
        $xml .= " NORMDOCID=\"{$data['NORMDOCID']}\"";
        $xml .= " DOCNAME=\"{$data['DOCNAME']}\"";
        $xml .= ' DOCDATE="' . $data['DOCDATE']->format('d.m.Y') . '"';
        $xml .= " DOCNUM=\"{$data['DOCNUM']}\"";
        $xml .= " DOCTYPE=\"{$data['DOCTYPE']}\"";
        $xml .= ' NEVER_GET_ME="NEVER_GET_ME"';
        $xml .= ' />';

        return [$data, $xml];
    }

    /**
     * Возвращает объект маппера.
     */
    protected function getMapper(): MapperInterface
    {
        return new NormativeDocument;
    }
}
