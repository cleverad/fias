<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\service\filesystem\DirectoryInterface;
use marvin255\fias\service\db\ConnectionInterface;

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
        $files = $dir->findFilesByPattern($this->mapper->getInsertFileMask());
        $file = reset($files);

        return $file;
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
        $this->info('Truncating ' . $this->mapper->getSqlName() . ' before inserting');
        $this->db->truncateTable($this->mapper);
    }

    /**
     * @inheritdoc
     */
    protected function getScenario(): string
    {
        return ConnectionInterface::SCENARIO_INSERT;
    }
}
