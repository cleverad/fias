<?php

declare(strict_types=1);

namespace marvin255\fias;

use marvin255\fias\service\config\ConfigInterface;
use marvin255\fias\service\fias\InformerInterface;
use marvin255\fias\service\fias\Informer;
use marvin255\fias\service\downloader\DownloaderInterface;
use marvin255\fias\service\downloader\Curl;
use marvin255\fias\service\filesystem\DirectoryInterface;
use marvin255\fias\service\filesystem\Directory;
use marvin255\fias\service\unpacker\UnpackerInterface;
use marvin255\fias\service\unpacker\Rar;
use marvin255\fias\service\xml\ReaderInterface;
use marvin255\fias\service\xml\Reader;
use marvin255\fias\service\db\DbInterface;
use marvin255\fias\service\db\Mysql;
use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\task\Cleanup;
use marvin255\fias\task\DownloadFull;
use marvin255\fias\task\Unpack;
use marvin255\fias\task\CreateStructure;
use marvin255\fias\task\InsertData;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use PDO;
use InvalidArgumentException;

/**
 * Фабричный объект, который создает пайпы для соответствующих типов задач.
 */
class Factory
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Создает пайп для полной загрузки базы ФИАС.
     *
     * @return Pipe
     *
     * @throws Exception
     */
    public function createInstallPipe(): Pipe
    {
        $informer = $this->createInformer();
        $downloader = $this->createDownloader();
        $workDir = $this->createWorkDir();
        $unpacker = $this->createUnpacker();
        $reader = $this->createReader();
        $db = $this->createDb();
        $log = $this->createLog();
        $mappers = $this->getMappers();

        $pipe = new Pipe;

        $pipe->pipe(new DownloadFull($informer, $downloader, $workDir, $log));
        $pipe->pipe(new Unpack($unpacker, $workDir, $log));

        $isStructureNeeded = $this->config->getBool('create_structure', false);
        foreach ($mappers as $mapper) {
            if ($isStructureNeeded) {
                $pipe->pipe(new CreateStructure($db, $mapper, $log));
            }
            $pipe->pipe(new InsertData($reader, $db, $mapper, $log));
        }

        $pipe->setCleanup(new Cleanup($log));

        return $pipe;
    }

    /**
     * Возвращает список сущностей, которые будут использованы для задачи.
     *
     * @return AbstractMapper[]
     *
     * @throws InvalidArgumentException
     */
    protected function getMappers(): array
    {
        $return = [];

        foreach ($this->config->getArray('mappers') as $rawMapper) {
            if (is_string($rawMapper)) {
                $instantedMapper = (new ReflectionClass($rawMapper))->newInstance();
            } else {
                $instantedMapper = $rawMapper;
            }
            if (!($instantedMapper instanceof AbstractMapper)) {
                throw new InvalidArgumentException(
                    'Mapper must be successor of ' . AbstractMapper::class
                );
            }
            $return[] = $instantedMapper;
        }

        return $return;
    }

    /**
     * Создает объект информера, который получает мета данные от ФИАС.
     *
     * @return InformerInterface
     *
     * @throws Exception
     */
    protected function createInformer(): InformerInterface
    {
        return new Informer;
    }

    /**
     * Создает объект для загрузки файлов.
     *
     * @return DownloaderInterface
     */
    protected function createDownloader(): DownloaderInterface
    {
        return new Curl;
    }

    /**
     * Создает объект для рабочей директории пайпа.
     *
     * @return DirectoryInterface
     *
     * @throws Exception
     */
    protected function createWorkDir(): DirectoryInterface
    {
        return new Directory($this->config->getString('work_dir', ''));
    }

    /**
     * Создает объект для распаковки архива.
     *
     * @return UnpackerInterface
     *
     * @throws Exception
     */
    protected function createUnpacker(): UnpackerInterface
    {
        return new Rar;
    }

    /**
     * Создает объект для чтения xml.
     *
     * @return ReaderInterface
     *
     * @throws Exception
     */
    protected function createReader(): ReaderInterface
    {
        return new Reader;
    }

    /**
     * Создает объект для обращения к базе данных.
     *
     * @return DbInterface
     *
     * @throws Exception
     */
    protected function createDb(): DbInterface
    {
        $dsn = $this->config->getString('pdo_dsn', '');
        $user = $this->config->getString('pdo_user', '');
        $password = $this->config->getString('pdo_password', '');

        if (!$dsn) {
            throw new InvalidArgumentException('Empty pdo_dsn config parameter');
        }
        if (!$user) {
            throw new InvalidArgumentException('Empty pdo_user config parameter');
        }

        $pdo = new PDO($dsn, $user, $password);
        $butchInsertLimit = $this->config->getInt('pdo_butch_insert_limit', 50);

        return new Mysql($pdo, $butchInsertLimit);
    }

    /**
     * Создает объект для записи данных в лог.
     *
     * @return LoggerInterface|null
     */
    protected function createLog()
    {
        $logParameter = $this->config->getRaw('log');

        $log = null;
        if ($logParameter instanceof LoggerInterface) {
            $log = $logParameter;
        }

        return $log;
    }
}
