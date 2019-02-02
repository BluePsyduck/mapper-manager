<?php

declare(strict_types=1);

/**
 * The skeleton configuration of the mapper manager.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace BluePsyduck\MapperManager;

use BluePsyduck\MapperManager\Constant\ConfigKey;

return [
    ConfigKey::MAIN => [
        ConfigKey::ADAPTERS => [
            Adapter\StaticMapperAdapter::class,
            Adapter\DynamicMapperAdapter::class,
        ],
        ConfigKey::MAPPERS => [
            // MyFancyMapper::class,
        ],
    ],
];
