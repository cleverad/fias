<?php

declare(strict_types=1);

namespace marvin255\fias\service\db;

use marvin255\fias\mapper\SqlMapperInterface;
use PDO;

/**
 * Объект для взаймодействия с базой данных mysql.
 */
class PdoConnection implements ConnectionInterface
{
    /**
     * @var MysqlQueryRunner
     */
    protected $queryRunner;
    /**
     * @var int
     */
    protected $batchInsertLimit = 50;
    /**
     * @var mixed[]
     */
    protected $insertQueue = [];
    /**
     * @var string
     */
    protected $currentScenario = '';

    /**
     * Задает объект PDO для соединения с базой данных.
     *
     * @param PDO $pdo
     * @param int $batchInsertLimit
     */
    public function __construct(PDO $pdo, int $batchInsertLimit = 50)
    {
        $this->queryRunner = new MysqlQueryRunner($pdo);
        $this->batchInsertLimit = $batchInsertLimit;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function selectRow(SqlMapperInterface $mapper, array $item)
    {
        $table = $mapper->getSqlName();
        $select = array_keys($mapper->getMap());
        $where = $mapper->convertToStrings($mapper->mapPrimaries($item));

        $res = $this->queryRunner->selectRow($table, $select, $where);

        return $res ? $mapper->convertToData($res) : null;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function insert(SqlMapperInterface $mapper, array $item)
    {
        $table = $mapper->getSqlName();

        if (!isset($this->insertQueue[$table])) {
            $this->insertQueue[$table] = [];
        }

        $this->insertQueue[$table][] = $mapper->convertToStrings($mapper->mapArray($item));

        if (count($this->insertQueue[$table]) === $this->batchInsertLimit) {
            $this->flushInsert($table);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function update(SqlMapperInterface $mapper, array $item)
    {
        $table = $mapper->getSqlName();
        $update = $mapper->convertToStrings($mapper->mapNotPrimaries($item));
        $where = $mapper->convertToStrings($mapper->mapPrimaries($item));

        $this->queryRunner->update($table, $update, $where);
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function delete(SqlMapperInterface $mapper, array $item)
    {
        $table = $mapper->getSqlName();
        $where = $mapper->convertToStrings($mapper->mapPrimaries($item));

        $this->queryRunner->delete($table, $where);
    }

    /**
     * @inheritdoc
     */
    public function createTable(SqlMapperInterface $mapper)
    {
        $table = $mapper->getSqlName();
        $fields = $mapper->getMap();
        $primaries = $mapper->getSqlPrimary();
        $indexes = $mapper->getSqlIndexes();
        $partitionField = $mapper->getSqlPartitionField();
        $partitionCount = $mapper->getSqlPartitionsCount();

        $this->queryRunner->createTable($table, $fields, $primaries, $indexes, $partitionField, $partitionCount);
    }

    /**
     * @inheritdoc
     */
    public function dropTable(SqlMapperInterface $mapper)
    {
        $this->queryRunner->dropTable($mapper->getSqlName());
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function truncateTable(SqlMapperInterface $mapper)
    {
        $this->queryRunner->delete($mapper->getSqlName());
    }

    /**
     * @inheritdoc
     */
    public function begin(string $scenario = '')
    {
        $this->currentScenario = $scenario;

        if ($this->currentScenario === ConnectionInterface::SCENARIO_INSERT) {
            $this->queryRunner->beginInsert();
        }
    }

    /**
     * @inheritdoc
     */
    public function complete()
    {
        foreach ($this->insertQueue as $table => $items) {
            $this->flushInsert($table);
        }

        if ($this->currentScenario === ConnectionInterface::SCENARIO_INSERT) {
            $this->queryRunner->completeInsert();
        }

        $this->currentScenario = '';
    }

    /**
     * Отправляет очередь insert запросов.
     *
     * @param string $table
     *
     * @throws Exception
     *
     * @return void
     */
    protected function flushInsert(string $table)
    {
        $data = $this->insertQueue[$table];
        $this->queryRunner->batchInsert($table, $data);
        unset($this->insertQueue[$table]);
    }
}
