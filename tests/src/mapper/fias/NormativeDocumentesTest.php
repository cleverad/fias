<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\fias\NormativeDocumentes;
use marvin255\fias\mapper\MapperInterface;
use DateTime;

/**
 * Тест маппера NormativeDocument.
 */
class NormativeDocumentesTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getTestData(): array
    {
        return [
            'NORMDOCID' => $this->faker()->uuid,
            'DOCNAME' => $this->faker()->text,
            'DOCDATE' => new DateTime($this->faker()->date),
            'DOCNUM' => $this->faker()->text,
            'DOCTYPE' => $this->faker()->uuid,
        ];
    }

    /**
     * Возвращает строку с xml на основании входного параметра.
     */
    protected function getTestXml(array $data): string
    {
        $xml = '<NormativeDocument';
        $xml .= " NORMDOCID=\"{$data['NORMDOCID']}\"";
        $xml .= " DOCNAME=\"{$data['DOCNAME']}\"";
        $xml .= ' DOCDATE="' . $data['DOCDATE']->format('d.m.Y') . '"';
        $xml .= " DOCNUM=\"{$data['DOCNUM']}\"";
        $xml .= " DOCTYPE=\"{$data['DOCTYPE']}\"";
        $xml .= ' NEVER_GET_ME="NEVER_GET_ME"';
        $xml .= ' />';

        return $xml;
    }

    /**
     * Возвращает объект маппера.
     */
    protected function getMapper(): MapperInterface
    {
        return new NormativeDocumentes;
    }
}
