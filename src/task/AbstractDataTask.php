<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\service\xml\ReaderInterface;
use marvin255\fias\service\db\DbInterface;
use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\service\filesystem\DirectoryInterface;
use marvin255\fias\service\filesystem\FileInterface;
use marvin255\fias\state\StateInterface;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;

/**
 * Абстрактный класс для задач, связанных с записью данных из xml в БД.
 */
abstract class AbstractDataTask extends AbstractTask
{
    /**
     * @var ReaderInterface
     */
    protected $reader;
    /**
     * @var DbInterface
     */
    protected $db;
    /**
     * @var AbstractMapper
     */
    protected $mapper;

    /**
     * Возвращает описание задачи для логов.
     *
     * @return string
     */
    abstract protected function getTaskDescription(): string;

    /**
     * Ищет файл, который следует импортировать, в указанной папке.
     *
     * @param DirectoryInterface $dir
     *
     * @return FileInterface|null
     */
    abstract protected function searchFileInDir(DirectoryInterface $dir);

    /**
     * Обрабатывает объект, который удалось прочитать из файла.
     *
     * @param array $item
     *
     * @return void
     */
    abstract protected function processItem(array $item);

    /**
     * @param ReaderInterface $reader
     * @param DbInterface     $db
     * @param AbstractMapper  $mapper
     * @param LoggerInterface $logger
     */
    public function __construct(ReaderInterface $reader, DbInterface $db, AbstractMapper $mapper, LoggerInterface $logger = null)
    {
        $this->reader = $reader;
        $this->db = $db;
        $this->mapper = $mapper;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function run(StateInterface $state)
    {
        $this->info($this->getTaskDescription());

        $extractedDir = $state->getParameter('extracted');
        if (!($extractedDir instanceof DirectoryInterface)) {
            throw new InvalidArgumentException(
                'There is no extracted dir in state object'
            );
        }

        $this->info('Searching xml file in ' . $extractedDir->getPath() . ' dir');
        $file = $this->searchFileInDir($extractedDir);

        if (!$file) {
            $this->info('Xml file not found, skipping');
        } else {
            $this->processFile($file);
        }
    }

    /**
     * Обрабатывает xml файл.
     *
     * @param FileInterface $file
     *
     * @return void
     *
     * @throws RuntimeException
     */
    protected function processFile(FileInterface $file)
    {
        $this->info('Reading ' . $file->getPath() . ' file');

        $this->reader->setMapper($this->mapper);
        if (!$this->reader->openFile($file->getPath())) {
            throw new RuntimeException(
                "Can't open xml file " . $file->getPath() . ' for reading'
            );
        }

        $this->beforeRead();
        $processedItems = 0;
        foreach ($this->reader as $item) {
            $this->processItem($item);
            ++$processedItems;
        }
        $this->db->complete();
        $this->afterRead();

        $this->info(
            "Reading and processing complete, {$processedItems} items processed"
        );

        $this->reader->closeFile();
    }

    /**
     * Событие, которое запускается перед чтением файла.
     *
     * @return void
     */
    protected function beforeRead()
    {
    }

    /**
     * Событие, которое запускается после чтения файла.
     *
     * @return void
     */
    protected function afterRead()
    {
    }
}
