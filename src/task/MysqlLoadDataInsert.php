<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\service\xml\ReaderInterface;
use marvin255\fias\service\filesystem\DirectoryInterface;
use marvin255\fias\service\filesystem\FileInterface;
use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\state\StateInterface;
use Psr\Log\LoggerInterface;
use PDO;
use InvalidArgumentException;

/**
 * Загрузка данных из файла в mysql с помощью встроенной в mysql функции
 * LOAD DATA.
 *
 * @see https://dev.mysql.com/doc/refman/5.7/en/load-data.html
 */
class MysqlLoadDataInsert extends AbstractTask
{
    /**
     * @var ReaderInterface
     */
    protected $reader;
    /**
     * @var PDO
     */
    protected $db;
    /**
     * @var AbstractMapper
     */
    protected $mapper;
    /**
     * Разделитель столбцов для csv.
     *
     * @var string
     */
    protected $delimiter = ',';
    /**
     * Ограничитель полей для csv.
     *
     * @var string
     */
    protected $enclosure = '"';
    /**
     * Экранирующий символ полей для csv.
     *
     * @var string
     */
    protected $escapeChar = '\\';
    /**
     * Разделитель строк для csv.
     */
    protected $linesDelimiter = '\n';

    public function __construct(ReaderInterface $reader, PDO $db, AbstractMapper $mapper, LoggerInterface $logger = null)
    {
        $this->reader = $reader;
        $this->db = $db;
        $this->mapper = $mapper;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function run(StateInterface $state)
    {
        $this->info('Loading data with LOAD DATA operator');

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
     * Ищет файл, который следует импортировать, в указанной папке.
     *
     * @param DirectoryInterface $dir
     *
     * @return FileInterface|null
     */
    protected function searchFileInDir(DirectoryInterface $dir)
    {
        $files = $dir->findFilesByPattern($this->mapper->getInsertFileMask());
        $file = reset($files);

        return $file;
    }

    /**
     * Читает найденный файл, записывает временный csv с данными и запускает
     * LOAD DATA запрос.
     *
     * @return void
     *
     * @throws RuntimeException
     */
    protected function processFile(FileInterface $file)
    {
        $this->openFileInReader($file);
        $csvFileName = $this->getCsvFileName($file);

        try {
            $this->createTemporaryCsvFile($csvFileName);
            $this->runLoadDataQuery($csvFileName);
        } finally {
            unlink($csvFileName);
        }
    }

    /**
     * Открывает xml файл в ридере.
     *
     * @return void
     *
     * @throws RuntimeException
     */
    protected function openFileInReader(FileInterface $file)
    {
        $this->info('Opening ' . $file->getPath() . ' file');

        $this->reader->setMapper($this->mapper);
        if (!$this->reader->openFile($file->getPath())) {
            throw new RuntimeException(
                "Can't open xml file " . $file->getPath() . ' for reading'
            );
        }
    }

    /**
     * Возвращает путь ко временному файлу csv.
     *
     * @throws RuntimeException
     */
    protected function getCsvFileName(FileInterface $file): string
    {
        $fileName = $file->getFilename();
        $tempDir = sys_get_temp_dir();
        if (!$tempDir || !is_writable($tempDir)) {
            throw new RuntimeException(
                "Can't find or write temporary folder: {$tempDir}"
            );
        }

        return "{$tempDir}/{$fileName}.csv";
    }

    /**
     * Создает временный csv файл и наполняет его данными.
     *
     * @return void
     *
     * @throws RuntimeException
     */
    protected function createTemporaryCsvFile(string $fileName)
    {
        $this->info("Creating temporary csv file {$fileName}");

        $fh = fopen($fileName, 'w');
        if ($fh === false) {
            throw new RuntimeException(
                "Can't open {$fileName} file for writing"
            );
        }

        foreach ($this->reader as $item) {
            $item = $this->mapper->convertToStrings($item);
            fputcsv($fh, $item, $this->delimiter, $this->enclosure, $this->escapeChar);
        }

        fclose($fh);

        $this->info("Temporary csv file {$fileName} created");
    }

    /**
     * Запускает запрос LOAD DATA.
     *
     * @return void
     *
     * @throws RuntimeException
     */
    protected function runLoadDataQuery(string $pathToCsv)
    {
        $this->info('Running LOAD DATA query');

        $escapedTableName = '`' . str_replace('`', '', $this->mapper->getSqlName()) . '`';
        $escapedCols = '`' . implode('`, `', array_keys($this->mapper->getMap())) . '`';
        $escapedPathToCsv = $this->db->quote($pathToCsv);

        $sql = "LOAD DATA CONCURRENT INFILE {$escapedPathToCsv} INTO TABLE {$escapedTableName} ({$escapedCols})";
        $sql .= ' LINES TERMINATED BY ' . $this->db->quote($this->linesDelimiter) . " STARTING BY ''";
        $sql .= ' FIELDS TERMINATED BY ' . $this->db->quote($this->delimiter)
            . ' ENCLOSED BY ' . $this->db->quote($this->enclosure)
            . ' ESCAPED BY ' . $this->db->quote($this->escapeChar)
        ;

        $this->db->exec($sql);

        $this->info('Query complete');
    }
}
