<?php

declare(strict_types=1);

namespace marvin255\fias\task;

use marvin255\fias\service\db\DbInterface;
use marvin255\fias\mapper\AbstractMapper;
use marvin255\fias\state\StateInterface;
use Psr\Log\LoggerInterface;

/**
 * Задача для создание структры в БД для указанного маппера.
 */
class CreateStructure extends AbstractTask
{
    /**
     * @var DbInterface
     */
    protected $db;
    /**
     * @var AbstractMapper
     */
    protected $mapper;

    /**
     * @param DbInterface     $db
     * @param AbstractMapper  $mapper
     * @param LoggerInterface $logger
     */
    public function __construct(DbInterface $db, AbstractMapper $mapper, LoggerInterface $logger = null)
    {
        $this->db = $db;
        $this->mapper = $mapper;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function run(StateInterface $state)
    {
        $this->info('Creating structure for ' . $this->mapper->getSqlName());

        $this->info('Deleting old ' . $this->mapper->getSqlName() . ' if exists');
        $this->db->dropTable($this->mapper);

        $this->info('Creating new ' . $this->mapper->getSqlName());
        $this->db->createTable($this->mapper);

        $this->info('Structure for ' . $this->mapper->getSqlName() . ' successfully created');
    }
}
