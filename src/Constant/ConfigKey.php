<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Constant;

/**
 * The interface holding the keys used in the config.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
interface ConfigKey
{
    /**
     * The main key to use, containing all the config for the mapper manager.
     */
    public const MAIN = 'mapper-manager';

    /**
     * The key containing the aliases of the adapters to add to the manager.
     */
    public const ADAPTERS = 'adapters';

    /**
     * The key containing the aliases of the mappers to add to the manager.
     */
    public const MAPPERS = 'mappers';
}
