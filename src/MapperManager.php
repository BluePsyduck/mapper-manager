<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager;

use BluePsyduck\MapperManager\Exception\InvalidMapperException;
use BluePsyduck\MapperManager\Exception\MapperException;
use BluePsyduck\MapperManager\Exception\MissingMapperException;
use BluePsyduck\MapperManager\Mapper\DynamicMapperInterface;
use BluePsyduck\MapperManager\Mapper\MapperInterface;
use BluePsyduck\MapperManager\Mapper\StaticMapperInterface;

/**
 * The manager of the mappers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class MapperManager implements MapperManagerInterface
{
    /**
     * The static mappers.
     * @var array|StaticMapperInterface[][]
     */
    protected $staticMappers = [];

    /**
     * The dynamic mappers.
     * @var array|DynamicMapperInterface[]
     */
    protected $dynamicMappers = [];

    /**
     * Adds a mapper to the manager.
     * @param MapperInterface $mapper
     * @throws MapperException
     */
    public function addMapper(MapperInterface $mapper): void
    {
        if ($mapper instanceof StaticMapperInterface) {
            $this->addStaticMapper($mapper);
        } elseif ($mapper instanceof DynamicMapperInterface) {
            $this->addDynamicMapper($mapper);
        } else {
            throw new InvalidMapperException(get_class($mapper));
        }
    }

    /**
     * Adds a static mapper to the manager.
     * @param StaticMapperInterface $mapper
     */
    protected function addStaticMapper(StaticMapperInterface $mapper): void
    {
        $sourceClass = $mapper->getSourceClass();
        if (!isset($this->staticMappers[$sourceClass])) {
            $this->staticMappers[$sourceClass] = [];
        }
        $this->staticMappers[$sourceClass][$mapper->getDestinationClass()] = $mapper;
    }

    /**
     * Adds a dynamic mapper to the manager.
     * @param DynamicMapperInterface $mapper
     */
    protected function addDynamicMapper(DynamicMapperInterface $mapper): void
    {
        $this->dynamicMappers[] = $mapper;
    }

    /**
     * Maps the source object into the destination one.
     * @param object $source
     * @param object $destination
     * @throws MapperException
     */
    public function map($source, $destination): void
    {
        $success = $this->mapWithStaticMapper($source, $destination);
        if (!$success) {
            $success = $this->mapWithDynamicMapper($source, $destination);
        }
        if (!$success) {
            throw new MissingMapperException(get_class($source), get_class($destination));
        }
    }

    /**
     * Trues to map the source to the destination using a static mapper and will return the success as result.
     * @param object $source
     * @param object $destination
     * @return bool
     */
    protected function mapWithStaticMapper($source, $destination): bool
    {
        $result = false;
        $sourceClass = get_class($source);
        $destinationClass = get_class($destination);
        if (isset($this->staticMappers[$sourceClass][$destinationClass])) {
            $this->staticMappers[$sourceClass][$destinationClass]->map($source, $destination);
            $result = true;
        }
        return $result;
    }

    /**
     * Tries to map the source to the destination using a dynamic mapper and will return the success as result.
     * @param object $source
     * @param object $destination
     * @return bool
     */
    protected function mapWithDynamicMapper($source, $destination): bool
    {
        $result = false;
        foreach ($this->dynamicMappers as $dynamicMapper) {
            if ($dynamicMapper->supports($source, $destination)) {
                $dynamicMapper->map($source, $destination);
                $result = true;
                break;
            }
        }
        return $result;
    }
}
