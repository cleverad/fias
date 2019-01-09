<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\MapperInterface;
use marvin255\fias\mapper\fias\FlatTypes;

/**
 * Тест маппера FlatTypes.
 */
class FlatTypesTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getTestData(): array
    {
        return [
            'FLTYPEID' => $this->faker()->randomDigit + 1,
            'NAME' => $this->faker()->word,
            'SHORTNAME' => $this->faker()->word,
        ];
    }

    /**
     * Возвращает строку с xml на основании входного параметра.
     */
    protected function getTestXml(array $data): string
    {
        $xml = '<FlatType';
        $xml .= " FLTYPEID=\"{$data['FLTYPEID']}\"";
        $xml .= " NAME=\"{$data['NAME']}\"";
        $xml .= " SHORTNAME=\"{$data['SHORTNAME']}\"";
        $xml .= ' NEVER_GET_ME="NEVER_GET_ME"';
        $xml .= ' />';

        return $xml;
    }

    /**
     * Возвращает объект маппера.
     */
    protected function getMapper(): MapperInterface
    {
        return new FlatTypes;
    }
}
