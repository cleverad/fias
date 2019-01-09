<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\service\filesystem\DirectoryInterface;

/**
 * Задача для обновления данных, указанных в файле, в БД.
 */
class UpdateData extends AbstractDataTask
{
    /**
     * @inheritdoc
     */
    protected function getTaskDescription(): string
    {
        return 'Updating data in ' . $this->mapper->getSqlName();
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
        $searchRes = $this->db->selectRow($this->mapper, $item);

        if ($searchRes === null) {
            $this->db->insert($this->mapper, $item);
        } else {
            $this->db->update($this->mapper, $item);
        }
    }
}
