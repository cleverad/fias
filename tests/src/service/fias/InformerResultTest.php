<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\fias;

use marvin255\fias\tests\BaseTestCase;
use marvin255\fias\service\fias\InformerResult;
use InvalidArgumentException;

/**
 * Тест для объекта, который представляет результат со ссылкой на файлы
 * от сервиса ФИАС.
 */
class InformerResultTest extends BaseTestCase
{
    /**
     * Проверяет геттры и сеттеры.
     */
    public function testGettersAnsSetters()
    {
        $version = $this->faker()->unique()->randomNumber + 1;
        $url = $this->faker()->unique()->url;

        $res = new InformerResult;
        $res->setVersion($version);
        $res->setUrl($url);

        $this->assertSame($version, $res->getVersion());
        $this->assertSame($url, $res->getUrl());
    }

    /**
     * Проверяет метод, который возвращает наличие результата в объекте.
     */
    public function testHasResult()
    {
        $res = new InformerResult;

        $this->assertFalse($res->hasResult());

        $res->setVersion($this->faker()->unique()->randomNumber + 1);
        $res->setUrl($this->faker()->unique()->url);

        $this->assertTrue($res->hasResult());
    }

    /**
     * Проверяет, чтобы сеттер для url выбрасывал исключение при попытке
     * ввести не url.
     */
    public function testSetUrlWrongFormatException()
    {
        $res = new InformerResult;

        $this->expectException(InvalidArgumentException::class);
        $res->setUrl($this->faker()->unique()->word);
    }
}
