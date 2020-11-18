<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager;

use BluePsyduck\MapperManager\Adapter\MapperAdapterInterface;
use BluePsyduck\MapperManager\Constant\ConfigKey;
use BluePsyduck\MapperManager\Exception\MapperException;
use BluePsyduck\MapperManager\Mapper\MapperInterface;
use Psr\Container\ContainerInterface;

/**
 * The factory of the mapper manager.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class MapperManagerFactory
{
    /**
     * Creates the mapper manager.
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array<mixed>|null $options
     * @return MapperManagerInterface
     * @throws MapperException
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        ?array $options = null
    ): MapperManagerInterface {
        $config = $container->get('config');

        $manager = new MapperManager();
        $this->addAdaptersToManager($manager, $container, $config);
        $this->addMappersToManager($manager, $container, $config);
        return $manager;
    }

    /**
     * Adds the adapters from the config to the manager.
     * @param MapperManager $manager
     * @param ContainerInterface $container
     * @param array<mixed> $config
     */
    protected function addAdaptersToManager(
        MapperManager $manager,
        ContainerInterface $container,
        array $config
    ): void {
        foreach (array_unique($config[ConfigKey::MAIN][ConfigKey::ADAPTERS] ?? []) as $alias) {
            /** @var MapperAdapterInterface<MapperInterface<object, object>> $adapter */
            $adapter = $container->get($alias);
            $manager->addAdapter($adapter);
        }
    }

    /**
     * Adds the mappers from the config to the manager.
     * @param MapperManager $manager
     * @param ContainerInterface $container
     * @param array<mixed> $config
     * @throws MapperException
     */
    protected function addMappersToManager(
        MapperManager $manager,
        ContainerInterface $container,
        array $config
    ): void {
        foreach (array_unique($config[ConfigKey::MAIN][ConfigKey::MAPPERS] ?? []) as $alias) {
            $manager->addMapper($container->get($alias));
        }
    }
}
