<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager;

use BluePsyduck\MapperManager\Exception\MapperException;
use BluePsyduck\MapperManager\Exception\MissingAdapterException;
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
    /** @var array<class-string, MapperAdapterInterface<MapperInterface<object, object>>> */
    protected array $adapters = [];

    /**
     * Adds an adapter to the mapper manager.
     * @param MapperAdapterInterface<MapperInterface<object, object>> $adapter
     */
    public function addAdapter(MapperAdapterInterface $adapter): void
    {
        $this->injectMapperManager($adapter);
        $this->adapters[$adapter->getHandledMapperInterface()] = $adapter;
    }

    /**
     * @throws MapperException
     */
    public function addMapper(MapperInterface $mapper): void
    {
        $this->injectMapperManager($mapper);
        foreach ($this->adapters as $handledMapperInterface => $adapter) {
            if ($mapper instanceof $handledMapperInterface) {
                $adapter->addMapper($mapper);
                return;
            }
        }

        throw new MissingAdapterException(get_class($mapper));
    }

    /**
     * @throws MapperException
     */
    public function map(object $source, object $destination): object
    {
        foreach ($this->adapters as $adapter) {
            $success = $adapter->map($source, $destination);
            if ($success) {
                return $destination;
            }
        }

        throw new MissingMapperException(get_class($source), get_class($destination));
    }

    /**
     * @throws MapperException
     */
    public function mapList(iterable $sources, callable|string $destinationCreator): array
    {
        if (is_string($destinationCreator)) {
            $destinationCreator = fn() => new $destinationCreator();
        }

        $result = [];
        foreach ($sources as $key => $source) {
            $result[$key] = $this->map($source, $destinationCreator());
        }
        return $result;
    }

    private function injectMapperManager(object $object): void
    {
        if ($object instanceof MapperManagerAwareInterface) {
            $object->setMapperManager($this);
        }
    }
}
