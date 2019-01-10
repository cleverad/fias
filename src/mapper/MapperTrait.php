<?php

declare(strict_types=1);

namespace marvin255\fias\mapper;

use marvin255\fias\mapper\field\FieldInterface;
use marvin255\fias\mapper\field\Line;
use marvin255\fias\mapper\field\Date;
use marvin255\fias\mapper\field\IntNumber;
use marvin255\fias\mapper\field\Uuid;
use ReflectionClass;
use UnexpectedValueException;
use Exception;

/**
 * Трэйт для объекта, который возвращает список полей для сущности ФИАС.
 * Служит преждевсего для получения результатов из xml и записи их в базу данных.
 */
trait MapperTrait
{
    /**
     * Возвращает массив с описанием полей сущности.
     *
     * Массив состоит из нулевого элемента - имени класса поля и неограниченного
     * количества других элементов, который будут переданы в качестве аргументов
     * конструктора.
     *
     * @var mixed[]
     */
    protected $fields = [];
    /**
     * Массив инициированных полей сущности.
     *
     * Для полей настрена отложенная инициализация, данный массив является служебным
     * для реализации отложенной инициализации.
     *
     * @var array|null
     */
    protected $initializedFields;
    /**
     * Массив псевдонимов для классов полей.
     *
     * @var string[]
     */
    protected $fieldsAliases = [
        'string' => Line::class,
        'date' => Date::class,
        'int' => IntNumber::class,
        'uuid' => Uuid::class,
    ];

    /**
     * Возвращает список полей данной сущности.
     *
     * @return FieldInterface[]
     *
     * @throws UnexpectedValueException
     */
    public function getMap(): array
    {
        if (!is_array($this->initializedFields)) {
            $this->initializedFields = [];
            foreach ($this->fields as $fieldName => $fieldDescription) {
                if (!is_array($fieldDescription)) {
                    $fieldDescription = [$fieldDescription];
                }
                $this->initializedFields[$fieldName] = $this->initializeField($fieldDescription);
            }
        }

        return $this->initializedFields;
    }

    /**
     * Убирает из входящего массива все поля, ключей для которых нет в списке
     * полей для данного маппера.
     *
     * @param array $messyArray
     *
     * @return array
     *
     * @throws UnexpectedValueException
     */
    public function mapArray(array $messyArray): array
    {
        $map = $this->getMap();
        $mappedArray = [];

        foreach ($map as $fieldName => $field) {
            $mappedArray[$fieldName] = $messyArray[$fieldName] ?? null;
        }

        return $mappedArray;
    }

    /**
     * Приводит значения к строковым представлениям.
     *
     * @param array $messyArray
     *
     * @return array
     *
     * @throws UnexpectedValueException
     */
    public function convertToStrings(array $messyArray): array
    {
        $map = $this->getMap();
        $convertedArray = [];

        foreach ($messyArray as $fieldName => $value) {
            try {
                $convertedArray[$fieldName] = isset($map[$fieldName])
                    ? $map[$fieldName]->convertToString($value)
                    : $value;
            } catch (Exception $e) {
                throw new UnexpectedValueException(
                    "Convert to string error, field {$fieldName}. " . $e->getMessage()
                );
            }
        }

        return $convertedArray;
    }

    /**
     * Приводит значения к php представлениям.
     *
     * @param array $messyArray
     *
     * @return array
     *
     * @throws UnexpectedValueException
     */
    public function convertToData(array $messyArray): array
    {
        $map = $this->getMap();
        $convertedArray = [];

        foreach ($messyArray as $fieldName => $value) {
            try {
                $convertedArray[$fieldName] = isset($map[$fieldName])
                    ? $map[$fieldName]->convertToData($value)
                    : $value;
            } catch (Exception $e) {
                throw new UnexpectedValueException(
                    "Convert to data error, field {$fieldName}. " . $e->getMessage()
                );
            }
        }

        return $convertedArray;
    }

    /**
     * Инициирует объект поля по его описанию.
     *
     * @param array $init
     *
     * @return FieldInterface
     *
     * @throws UnexpectedValueException
     */
    protected function initializeField(array $init): FieldInterface
    {
        $class = array_shift($init);
        if (isset($this->fieldsAliases[$class])) {
            $class = $this->fieldsAliases[$class];
        }

        $reflection = new ReflectionClass($class);
        $object = $reflection->newInstanceArgs($init);

        if (!($object instanceof FieldInterface)) {
            throw new UnexpectedValueException(
                'Field must be instance of ' . FieldInterface::class . " got {$class}"
            );
        }

        return $object;
    }
}
