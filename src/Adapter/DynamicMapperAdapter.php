<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Adapter;

use BluePsyduck\MapperManager\Mapper\DynamicMapperInterface;

/**
 * The handler for the dynamic mappers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class DynamicMapperAdapter implements MapperAdapterInterface
{
    /**
     * The mappers of the adapter.
     * @var array|DynamicMapperInterface[]
     */
    protected $mappers = [];

    /**
     * Returns the mapper interface which this adapter will handle.
     * @return string
     */
    public function getHandledInterface(): string
    {
        return DynamicMapperInterface::class;
    }

    /**
     * Adds a mapper to the adapter.
     * @param DynamicMapperInterface $mapper
     */
    public function addMapper($mapper): void
    {
        $this->mappers[] = $mapper;
    }

    /**
     * Tries to map the source object into the destination one.
     * @param object $source
     * @param object $destination
     * @return bool Whether the adapter was actually able to map the objects.
     */
    public function map($source, $destination): bool
    {
        $result = false;
        foreach ($this->mappers as $mapper) {
            if ($mapper->supports($source, $destination)) {
                $mapper->map($source, $destination);
                $result = true;
                break;
            }
        }
        return $result;
    }
}
