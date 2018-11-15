<?php

declare(strict_types=1);

namespace marvin255\fias\tests\mapper\fias;

use marvin255\fias\mapper\MapperInterface;
use marvin255\fias\mapper\fias\AddressObjectTypes;

/**
 * Тест маппера AddressObjectTypes.
 */
class AddressObjectTypesTest extends MapperCase
{
    /**
     * Возвращает данные для проверки извлечения из xml.
     */
    protected function getTestData(): array
    {
        return [
            'KOD_T_ST' => $this->faker()->randomDigit,
            'LEVEL' => $this->faker()->randomDigit,
            'SOCRNAME' => $this->faker()->word,
            'SCNAME' => $this->faker()->word,
        ];
    }

    /**
     * Возвращает строку с xml на основании входного параметра.
     */
    protected function getTestXml(array $data): string
    {
        $xml = '<AddressObjectType';
        $xml .= " KOD_T_ST=\"{$data['KOD_T_ST']}\"";
        $xml .= " LEVEL=\"{$data['LEVEL']}\"";
        $xml .= " SOCRNAME=\"{$data['SOCRNAME']}\"";
        $xml .= " SCNAME=\"{$data['SCNAME']}\"";
        $xml .= ' NEVER_GET_ME="NEVER_GET_ME"';
        $xml .= ' />';

        return $xml;
    }

    /**
     * Возвращает объект маппера.
     */
    protected function getMapper(): MapperInterface
    {
        return new AddressObjectTypes;
    }
}
