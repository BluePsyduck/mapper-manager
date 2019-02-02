<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Adapter;

use BluePsyduck\MapperManager\Mapper\StaticMapperInterface;

/**
 * The handler for the static mappers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class StaticMapperAdapter implements MapperAdapterInterface
{
    /**
     * The mappers of the handler.
     * @var array|StaticMapperInterface[][]
     */
    protected $mappers = [];

    /**
     * Returns the interface which this adapter will handle.
     * @return string
     */
    public function getHandledInterface(): string
    {
        return StaticMapperInterface::class;
    }

    /**
     * Adds a mapper to the adapter.
     * @param StaticMapperInterface $mapper
     */
    public function addMapper($mapper): void
    {
        $sourceClass = $mapper->getSourceClass();
        if (!isset($this->staticMappers[$sourceClass])) {
            $this->mappers[$sourceClass] = [];
        }
        $this->mappers[$sourceClass][$mapper->getDestinationClass()] = $mapper;
    }

    /**
     * Tries to map the source object into the destination one.
     * @param object $source
     * @param object $destination
     * @return bool Whether the adapter was actually able to map the objects.
     */
    public function map($source, $destination): bool
    {
        $sourceClass = get_class($source);
        $destinationClass = get_class($destination);

        $result = false;
        if (isset($this->staticMappers[$sourceClass][$destinationClass])) {
            $this->mappers[$sourceClass][$destinationClass]->map($source, $destination);
            $result = true;
        }
        return $result;
    }
}
