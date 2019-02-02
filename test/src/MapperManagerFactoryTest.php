<?php

declare(strict_types=1);

namespace BluePsyduckTest\MapperManager;

use BluePsyduck\Common\Test\ReflectionTrait;
use BluePsyduck\MapperManager\Adapter\MapperAdapterInterface;
use BluePsyduck\MapperManager\Constant\ConfigKey;
use BluePsyduck\MapperManager\Exception\MapperException;
use BluePsyduck\MapperManager\Mapper\MapperInterface;
use BluePsyduck\MapperManager\MapperManager;
use BluePsyduck\MapperManager\MapperManagerFactory;
use BluePsyduck\MapperManager\MapperManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionException;

/**
 * The PHPUnit test of the MapperManagerFactory class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \BluePsyduck\MapperManager\MapperManagerFactory
 */
class MapperManagerFactoryTest extends TestCase
{
    use ReflectionTrait;

    /**
     * Tests the invoking.
     * @throws MapperException
     * @covers ::__invoke
     */
    public function testInvoke(): void
    {
        $config = ['abc' => 'def'];

        /* @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
                  ->method('get')
                  ->with($this->identicalTo('config'))
                  ->willReturn($config);

        /* @var MapperManagerFactory&MockObject $factory */
        $factory = $this->getMockBuilder(MapperManagerFactory::class)
                        ->setMethods(['addAdaptersToManager', 'addMappersToManager'])
                        ->getMock();
        $factory->expects($this->once())
                ->method('addAdaptersToManager')
                ->with(
                    $this->isInstanceOf(MapperManager::class),
                    $this->identicalTo($container),
                    $this->identicalTo($config)
                );
        $factory->expects($this->once())
                ->method('addMappersToManager')
                ->with(
                    $this->isInstanceOf(MapperManager::class),
                    $this->identicalTo($container),
                    $this->identicalTo($config)
                );

        $result = $factory($container, MapperManagerInterface::class);
        $this->assertInstanceOf(MapperManager::class, $result);
    }

    /**
     * Tests the addAdaptersToManager method.
     * @throws ReflectionException
     * @covers ::addAdaptersToManager
     */
    public function testAddAdaptersToManager(): void
    {
        $config = [
            ConfigKey::MAIN => [
                ConfigKey::ADAPTERS => [
                    'foo',
                    'bar',
                ],
            ],
        ];

        /* @var MapperAdapterInterface&MockObject $adapter1 */
        $adapter1 = $this->createMock(MapperAdapterInterface::class);
        /* @var MapperAdapterInterface&MockObject $adapter2 */
        $adapter2 = $this->createMock(MapperAdapterInterface::class);

        /* @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->exactly(2))
                  ->method('get')
                  ->withConsecutive(
                      [$this->identicalTo('foo')],
                      [$this->identicalTo('bar')]
                  )
                  ->willReturnOnConsecutiveCalls(
                      $adapter1,
                      $adapter2
                  );

        /* @var MapperManager&MockObject $manager */
        $manager = $this->createMock(MapperManager::class);
        $manager->expects($this->exactly(2))
                ->method('addAdapter')
                ->withConsecutive(
                    [$this->identicalTo($adapter1)],
                    [$this->identicalTo($adapter2)]
                );

        $factory = new MapperManagerFactory();
        $this->invokeMethod($factory, 'addAdaptersToManager', $manager, $container, $config);
    }
    
    /**
     * Tests the addMappersToManager method.
     * @throws ReflectionException
     * @covers ::addMappersToManager
     */
    public function testAddMappersToManager(): void
    {
        $config = [
            ConfigKey::MAIN => [
                ConfigKey::MAPPERS => [
                    'foo',
                    'bar',
                ],
            ],
        ];

        /* @var MapperInterface&MockObject $mapper1 */
        $mapper1 = $this->createMock(MapperInterface::class);
        /* @var MapperInterface&MockObject $mapper2 */
        $mapper2 = $this->createMock(MapperInterface::class);

        /* @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->exactly(2))
                  ->method('get')
                  ->withConsecutive(
                      [$this->identicalTo('foo')],
                      [$this->identicalTo('bar')]
                  )
                  ->willReturnOnConsecutiveCalls(
                      $mapper1,
                      $mapper2
                  );

        /* @var MapperManager&MockObject $manager */
        $manager = $this->createMock(MapperManager::class);
        $manager->expects($this->exactly(2))
                ->method('addMapper')
                ->withConsecutive(
                    [$this->identicalTo($mapper1)],
                    [$this->identicalTo($mapper2)]
                );

        $factory = new MapperManagerFactory();
        $this->invokeMethod($factory, 'addMappersToManager', $manager, $container, $config);
    }
}
