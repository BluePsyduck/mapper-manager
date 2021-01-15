<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Mapper;

/**
 * The base interface of the mapper. Implement one of the derived interfaces.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @internal
 *
 * @template TSrc of object
 * @template TDest of object
 */
interface MapperInterface
{
    /**
     * Maps the source object to the destination one.
     * @param TSrc $source
     * @param TDest $destination
     */
    public function map(object $source, object $destination): void;
}
