<?php

declare(strict_types=1);

namespace marvin255\fias\service\fias;

/**
 * Интерфейс для объекта, который предоставляет результат со ссылкой на файлы
 * от сервиса ФИАС.
 */
interface InformerResultInterface
{
    /**
     * Задает версию ФИАС, для которой получена ссылка.
     *
     * @param int $version
     *
     * @return \marvin255\fias\service\fias\InformerResultInterface
     */
    public function setVersion(int $version): InformerResultInterface;

    /**
     * Возвращает версию ФИАС, для которой получена ссылка.
     *
     * @return int
     */
    public function getVersion(): int;

    /**
     * Задает ссылку, по которой можно скачать файл.
     *
     * @param string $url
     *
     * @return \marvin255\fias\service\fias\InformerResultInterface
     *
     * @throws \InvalidArgumentException
     */
    public function setUrl(string $url): InformerResultInterface;

    /**
     * Получает ссылку, по которой можно скачать файл.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Проверяет содержит ли данный объект ответ от сервиса или нет.
     *
     * @return bool
     */
    public function hasResult(): bool;
}
