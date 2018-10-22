<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\service\filesystem\DirectoryInterface;

/**
 * Задача для удаляения данных, указанных в файле, из БД.
 */
class DeletetData extends AbstractDataTask
{
    /**
     * @inheritdoc
     */
    protected function getTaskDescription(): string
    {
        return 'Deleteing data from ' . $this->mapper->getSqlName();
    }

    /**
     * @inheritdoc
     */
    protected function searchFileInDir(DirectoryInterface $dir)
    {
        $files = $dir->findFilesByPattern($this->mapper->getDeleteFileMask());
        $file = reset($files);

        return $file;
    }

    /**
     * @inheritdoc
     */
    protected function processItem(array $item)
    {
        $this->db->delete($this->mapper, $item);
    }
}
