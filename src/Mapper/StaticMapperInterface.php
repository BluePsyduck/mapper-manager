<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Mapper;

/**
 * The interface of the static mappers, which always map exactly one class of object to another one.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
interface StaticMapperInterface extends MapperInterface
{
    /**
     * Returns the source class supported by this mapper.
     * @return string
     */
    public function getSupportedSourceClass(): string;

    /**
     * Returns the destination class supported by this mapper.
     * @return string
     */
    public function getSupportedDestinationClass(): string;
}
