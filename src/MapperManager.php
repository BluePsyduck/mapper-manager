<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager;

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
    protected $adapters = [];

    /**
     * Adds an adapter to the mapper manager.
     * @param MapperAdapterInterface<MapperInterface<object, object>> $adapter
     */
    public function addAdapter(MapperAdapterInterface $adapter): void
    {
        $this->injectMapperManager($adapter);
        $this->adapters[$adapter->getHandledMapperInterface()] = $adapter;
    }

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

    private function injectMapperManager(object $object): void
    {
        if ($object instanceof MapperManagerAwareInterface) {
            $object->setMapperManager($this);
        }
    }
}
