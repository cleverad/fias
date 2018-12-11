<?php

declare(strict_types=1);

namespace marvin255\fias\service\config;

use Symfony\Component\Yaml\Yaml;
use InvalidArgumentException;
use Exception;

/**
 * Объект, который получает конфигурацию из yaml файла и хранит ее во
 * внутреннем массиве.
 */
class YamlConfig extends ArrayConfig
{
    /**
     * @param string  $pathToYaml
     * @param mixed[] $additionalOptions
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $pathToYaml, array $additionalOptions = [])
    {
        $pathToYaml = $this->realpath($pathToYaml);

        $yamlOptions = $this->parseYamlFile($pathToYaml);
        $options = array_merge($yamlOptions, $additionalOptions);
        $options = $this->setReplaces($options, $pathToYaml);

        $this->options = $options;
    }

    /**
     * Проверяет, что опция не пустая и файл существует, возвращает абсолютный
     * каконизированный путь к файлу.
     *
     * @param string $pathToYaml
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    protected function realpath(string $pathToYaml)
    {
        if (!$pathToYaml || !file_exists($pathToYaml)) {
            throw new InvalidArgumentException(
                "Can't find {$pathToYaml} yaml file"
            );
        }

        return realpath($pathToYaml);
    }

    /**
     * Читает данные из yaml файла.
     *
     * @param string $pathToYaml
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    protected function parseYamlFile(string $pathToYaml): array
    {
        try {
            $yamlOptions = Yaml::parseFile($pathToYaml);
        } catch (Exception $e) {
            throw new InvalidArgumentException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        return $yamlOptions;
    }

    /**
     * Заменяет в опциях шаблоны вида @currDir на путь к текущей папке.
     *
     * Текущая папка вычисляется как так папка, в которой лежит файл конфигов.
     *
     * @param array  $options
     * @param string $pathToYaml
     *
     * @return array
     */
    protected function setReplaces(array $options, string $pathToYaml): array
    {
        $replaces = [
            '@currDir' => pathinfo($pathToYaml, PATHINFO_DIRNAME),
        ];

        return self::setReplacesToArray($options, $replaces);
    }

    /**
     * Производит замену плейсхолдеров на предопределенные значения.
     *
     * @param array $params
     * @param array $replaces
     *
     * @return array
     */
    protected static function setReplacesToArray(array $params, array $replaces)
    {
        $return = [];

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $return[$key] = self::setReplacesToArray($value, $replaces);
            } elseif (is_string($value)) {
                foreach ($replaces as $replaceKey => $replaceValue) {
                    if (strpos($value, $replaceKey) !== false) {
                        $value = str_replace($replaceKey, $replaceValue, $value);
                    }
                }
                $return[$key] = $value;
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }
}
