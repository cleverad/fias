<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\service\filesystem\DirectoryInterface;

/**
 * Задача для наполнения данных с нуля для указанного маппера.
 */
class InsertData extends AbstractDataTask
{
    /**
     * @inheritdoc
     */
    protected function getTaskDescription(): string
    {
        return 'Inserting new data to ' . $this->mapper->getSqlName();
    }

    /**
     * @inheritdoc
     */
    protected function searchFileInDir(DirectoryInterface $dir)
    {
    }

    /**
     * @inheritdoc
     */
    protected function processItem(array $item)
    {
        $this->db->insert($this->mapper, $item);
    }

    /**
     * @inheritdoc
     */
    protected function beforeRead()
    {
        if ($this->db->isTableExists($this->mapper)) {
            $this->info('Dropping ' . $this->mapper->getSqlName() . ' before inserting');
            $this->db->dropTable($this->mapper);
        }

        $this->info('Creating ' . $this->mapper->getSqlName() . ' before inserting');
        $this->db->createTable($this->mapper);
    }
}
