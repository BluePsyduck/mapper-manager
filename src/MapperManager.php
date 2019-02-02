<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager;

use BluePsyduck\MapperManager\Exception\MissingAdapterException;
use BluePsyduck\MapperManager\Exception\MapperException;
use BluePsyduck\MapperManager\Exception\MissingMapperException;
use BluePsyduck\MapperManager\Adapter\MapperAdapterInterface;
use BluePsyduck\MapperManager\Mapper\MapperInterface;

/**
 * The manager of the mappers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class MapperManager implements MapperManagerInterface
{
    /**
     * The adapters of the manager.
     * @var array|MapperAdapterInterface[]
     */
    protected $adapters;

    /**
     * Adds a adapter to the manager.
     * @param MapperAdapterInterface $adapter
     */
    public function addAdapter(MapperAdapterInterface $adapter): void
    {
        $this->adapters[$adapter->getHandledMapperInterface()] = $adapter;
    }

    /**
     * Adds a mapper to the manager.
     * @param MapperInterface $mapper
     * @throws MapperException
     */
    public function addMapper(MapperInterface $mapper): void
    {
        foreach ($this->adapters as $handledMapperInterface => $adapter) {
            if ($mapper instanceof $handledMapperInterface) {
                $adapter->addMapper($mapper);
                return;
            }
        }

        throw new MissingAdapterException(get_class($mapper));
    }

    /**
     * Maps the source object into the destination one.
     * @param object $source
     * @param object $destination
     * @throws MapperException
     */
    public function map($source, $destination): void
    {
        foreach ($this->adapters as $adapter) {
            $success = $adapter->map($source, $destination);
            if ($success) {
                return;
            }
        }

        throw new MissingMapperException(get_class($source), get_class($destination));
    }
}
