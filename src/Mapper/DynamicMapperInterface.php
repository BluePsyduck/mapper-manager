<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Mapper;

/**
 * The interface of the dynamic mappers, which decide on the actual objects whether they can map them or not.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
interface DynamicMapperInterface extends MapperInterface
{
    /**
     * Returns whether the mapper supports the combination of source and destination object.
     * @param object $source
     * @param object $destination
     * @return bool
     */
    public function supports($source, $destination): bool;
}
