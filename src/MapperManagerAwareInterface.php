<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager;

/**
 * The interface signaling the awareness of the mapper manager.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
interface MapperManagerAwareInterface
{
    /**
     * Sets the mapper manager.
     * @param MapperManagerInterface $mapperManager
     */
    public function setMapperManager(MapperManagerInterface $mapperManager): void;
}
