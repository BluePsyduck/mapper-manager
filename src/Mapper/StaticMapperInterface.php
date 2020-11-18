<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Mapper;

/**
 * The interface of the static mappers, which always map exactly one class of object to another one.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 *
 * @template TSrc of object
 * @template TDest of object
 * @extends MapperInterface<TSrc, TDest>
 */
interface StaticMapperInterface extends MapperInterface
{
    /**
     * Returns the source class supported by this mapper.
     * @return class-string<TSrc>
     */
    public function getSupportedSourceClass(): string;

    /**
     * Returns the destination class supported by this mapper.
     * @return class-string<TDest>
     */
    public function getSupportedDestinationClass(): string;

    /**
     * Maps the source object to the destination one.
     * @param TSrc $source
     * @param TDest $destination
     */
    public function map(object $source, object $destination): void;
}
