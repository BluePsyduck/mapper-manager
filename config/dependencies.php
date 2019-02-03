<?php

declare(strict_types=1);

/**
 * The configuration of the dependencies for the Zend Service Manager.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace BluePsyduck\MapperManager;

return [
    'dependencies' => [
        'aliases' => [
            MapperManager::class => MapperManagerInterface::class,
        ],
        'factories' => [
            MapperManagerInterface::class => MapperManagerFactory::class,
        ],
        'invokables' => [
            Adapter\DynamicMapperAdapter::class,
            Adapter\StaticMapperAdapter::class,
        ],
    ],
];
