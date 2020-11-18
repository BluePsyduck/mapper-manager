<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager;

/**
 * The trait implementing the awareness of the mapper manager.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
trait MapperManagerAwareTrait /* implements MapperManagerAwareInterface */
{
    protected /* MapperManagerInterface */ $mapperManager;

    public function setMapperManager(MapperManagerInterface $mapperManager): void
    {
        $this->mapperManager = $mapperManager;
    }
}
