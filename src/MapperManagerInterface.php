<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager;

use BluePsyduck\MapperManager\Mapper\MapperInterface;

/**
 * The interface of the mapper manager.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
interface MapperManagerInterface
{
    /**
     * Adds a mapper to the manager.
     * @template TSrc of object
     * @template TDest of object
     * @param MapperInterface<TSrc, TDest> $mapper
     */
    public function addMapper(MapperInterface $mapper): void;

    /**
     * Maps the source object into the destination one.
     * @template TSrc of object
     * @template TDest of object
     * @param TSrc $source
     * @param TDest $destination
     * @return TDest
     */
    public function map(object $source, object $destination): object;
}
