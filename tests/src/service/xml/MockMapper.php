<?php

declare(strict_types=1);

namespace marvin255\fias\tests\service\xml;

use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\mapper\field\Line;

/**
 * Мок класс для маппера xml.
 */
class MockMapper extends AbstractMapper
{
    /**
     * @var string
     */
    protected $path = '';
    /**
     * @var string[]
     */
    protected $map = [];

    /**
     * @param string   $path
     * @param string[] $map
     */
    public function __construct(string $path, array $map)
    {
        $this->path = $path;

        foreach ($map as $field) {
            $this->map[$field] = new Line;
        }
    }

    /**
     * @inheritdoc
     */
    public function getXmlPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    protected function createFields(): array
    {
        return $this->map;
    }

    /**
     * @inheritdoc
     */
    public function getSqlPrimary(): array
    {
        $fields = $this->getMap();
        $fieldsNames = array_keys($fields);

        return [reset($fieldsNames)];
    }

    /**
     * @inhertitdoc
     */
    public function getInsertFileMask(): string
    {
        return '';
    }

    /**
     * @inhertitdoc
     */
    public function getDeleteFileMask(): string
    {
        return '';
    }
}
