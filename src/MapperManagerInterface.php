<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager;

use BluePsyduck\MapperManager\Exception\MapperException;
use BluePsyduck\MapperManager\Mapper\MapperInterface;

/**
 * THe interface of the mapper manager.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
interface MapperManagerInterface
{
    /**
     * Adds a mapper to the manager.
     * @param MapperInterface $mapper
     * @throws MapperException
     */
    public function addMapper(MapperInterface $mapper): void;

    /**
     * Maps the source object into the destination one.
     * @param object $source
     * @param object $destination
     * @throws MapperException
     */
    public function map($source, $destination): void;
}
