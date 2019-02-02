<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager;

/**
 * The config provider for the mapper manager.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ConfigProvider
{
    /**
     * Returns the config of the mapper manager.
     * @return array
     */
    public function __invoke(): array
    {
        return array_merge(
            require(__DIR__ . '/../config/dependencies.php'),
            require(__DIR__ . '/../config/mapper-manager.php')
        );
    }
}
