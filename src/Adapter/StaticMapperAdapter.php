<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Adapter;

use BluePsyduck\MapperManager\Mapper\MapperInterface;
use BluePsyduck\MapperManager\Mapper\StaticMapperInterface;

/**
 * The handler for the static mappers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 *
 * @implements MapperAdapterInterface<StaticMapperInterface<object, object>>
 */
class StaticMapperAdapter implements MapperAdapterInterface
{
    /**
     * @var array<class-string<object>, array<class-string<object>, StaticMapperInterface<object, object>>>
     */
    protected array $mappers = [];

    public function getHandledMapperInterface(): string
    {
        return StaticMapperInterface::class;
    }

    /**
     * @param StaticMapperInterface<object, object> $mapper
     */
    public function addMapper(MapperInterface $mapper): void
    {
        $this->mappers[$mapper->getSupportedSourceClass()][$mapper->getSupportedDestinationClass()] = $mapper;
    }

    public function map(object $source, object $destination): bool
    {
        $sourceClass = get_class($source);
        $destinationClass = get_class($destination);

        if (isset($this->mappers[$sourceClass][$destinationClass])) {
            $this->mappers[$sourceClass][$destinationClass]->map($source, $destination);
            return true;
        }

        return false;
    }
}
