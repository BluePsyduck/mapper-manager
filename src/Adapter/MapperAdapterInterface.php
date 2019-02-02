<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Adapter;

use BluePsyduck\MapperManager\Mapper\MapperInterface;

/**
 * The interface of the mapper adapters.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
interface MapperAdapterInterface
{
    /**
     * Returns the mapper interface which this adapter will handle.
     * @return string
     */
    public function getHandledMapperInterface(): string;

    /**
     * Adds a mapper to the adapter.
     * @param MapperInterface $mapper
     */
    public function addMapper($mapper): void;

    /**
     * Tries to map the source object into the destination one.
     * @param object $source
     * @param object $destination
     * @return bool Whether the adapter was actually able to map the objects.
     */
    public function map($source, $destination): bool;
}
