<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

/**
 * Базовый класс для универсального маппера.
 */
abstract class AbstractMapper implements SqlMapperInterface, XmlMapperInterface
{
    use XmlMapperTrait, SqlMapperTrait {
        SqlMapperTrait::getMap insteadof XmlMapperTrait;
        SqlMapperTrait::mapArray insteadof XmlMapperTrait;
        SqlMapperTrait::convertToStrings insteadof XmlMapperTrait;
        SqlMapperTrait::initializeField insteadof XmlMapperTrait;
    }
}
