<?php

declare(strict_types=1);

namespace BluePsyduckTest\MapperManager;

use BluePsyduck\Common\Test\ReflectionTrait;
use BluePsyduck\MapperManager\Adapter\MapperAdapterInterface;
use BluePsyduck\MapperManager\Exception\MapperException;
use BluePsyduck\MapperManager\Exception\MissingAdapterException;
use BluePsyduck\MapperManager\Exception\MissingMapperException;
use BluePsyduck\MapperManager\Mapper\MapperInterface;
use BluePsyduck\MapperManager\Mapper\StaticMapperInterface;
use BluePsyduck\MapperManager\MapperManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use stdClass;

/**
 * The PHPUnit test of the MapperManager class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \BluePsyduck\MapperManager\MapperManager
 */
class MapperManagerTest extends TestCase
{
    use ReflectionTrait;

    /**
     * Tests the addAdapter method.
     * @throws ReflectionException
     * @covers ::addAdapter
     */
    public function testAddAdapter(): void
    {
        /* @var MapperAdapterInterface&MockObject $adapter1 */
        $adapter1 = $this->createMock(MapperAdapterInterface::class);

        /* @var MapperAdapterInterface&MockObject $adapter2 */
        $adapter2 = $this->createMock(MapperAdapterInterface::class);
        $adapter2->expects($this->once())
                 ->method('getHandledMapperInterface')
                 ->willReturn('bar');

        $manager = new MapperManager();
        $this->injectProperty($manager, 'adapters', ['foo' => $adapter1]);

        $manager->addAdapter($adapter2);
        $this->assertSame(['foo' => $adapter1, 'bar' => $adapter2], $this->extractProperty($manager, 'adapters'));
    }

    /**
     * Tests the addMapper method with a handling adapter.
     * @throws MapperException
     * @throws ReflectionException
     * @covers ::addMapper
     */
    public function testAddMapperWithHandlingAdapter(): void
    {
        /* @var StaticMapperInterface&MockObject $mapper */
        $mapper = $this->createMock(StaticMapperInterface::class);

        /* @var MapperAdapterInterface&MockObject $adapter1 */
        $adapter1 = $this->createMock(MapperAdapterInterface::class);
        $adapter1->expects($this->never())
                 ->method('addMapper');

        /* @var MapperAdapterInterface&MockObject $adapter2 */
        $adapter2 = $this->createMock(MapperAdapterInterface::class);
        $adapter2->expects($this->once())
                 ->method('addMapper')
                 ->with($this->identicalTo($mapper));

        /* @var MapperAdapterInterface&MockObject $adapter3 */
        $adapter3 = $this->createMock(MapperAdapterInterface::class);
        $adapter3->expects($this->never())
                 ->method('addMapper');

        $adapters = [
            'foo' => $adapter1, // No match.
            MapperInterface::class => $adapter2, // First match will be used.
            StaticMapperInterface::class => $adapter3, // Second match will be ignored.
        ];

        $manager = new MapperManager();
        $this->injectProperty($manager, 'adapters', $adapters);

        $manager->addMapper($mapper);
    }

    /**
     * Tests the addMapper method without a handling adapter.
     * @throws MapperException
     * @throws ReflectionException
     * @covers ::addMapper
     */
    public function testAddMapperWithoutHandlingAdapter(): void
    {
        /* @var MapperInterface&MockObject $mapper */
        $mapper = $this->createMock(MapperInterface::class);

        /* @var MapperAdapterInterface&MockObject $adapter */
        $adapter = $this->createMock(MapperAdapterInterface::class);
        $adapter->expects($this->never())
                ->method('addMapper');

        $manager = new MapperManager();
        $this->injectProperty($manager, 'adapters', ['foo' => $adapter]);

        $this->expectException(MissingAdapterException::class);

        $manager->addMapper($mapper);
    }

    /**
     * Tests the map method with a matching mapper.
     * @throws MapperException
     * @throws ReflectionException
     * @covers ::map
     */
    public function testMapWithMatchingMapper(): void
    {
        $source = new stdClass();
        $destination = new stdClass();

        /* @var MapperAdapterInterface&MockObject $adapter1 */
        $adapter1 = $this->createMock(MapperAdapterInterface::class);
        $adapter1->expects($this->once())
                 ->method('map')
                 ->with($source, $destination)
                 ->willReturn(false);

        /* @var MapperAdapterInterface&MockObject $adapter2 */
        $adapter2 = $this->createMock(MapperAdapterInterface::class);
        $adapter2->expects($this->once())
                 ->method('map')
                 ->with($source, $destination)
                 ->willReturn(true);

        /* @var MapperAdapterInterface&MockObject $adapter3 */
        $adapter3 = $this->createMock(MapperAdapterInterface::class);
        $adapter3->expects($this->never())
                 ->method('map');

        $manager = new MapperManager();
        $this->injectProperty($manager, 'adapters', [$adapter1, $adapter2, $adapter3]);

        $manager->map($source, $destination);
    }

    /**
     * Tests the map method without a matching mapper.
     * @throws MapperException
     * @throws ReflectionException
     * @covers ::map
     */
    public function testMapWithoutMatchingMapper(): void
    {
        $source = new stdClass();
        $destination = new stdClass();

        /* @var MapperAdapterInterface&MockObject $adapter */
        $adapter = $this->createMock(MapperAdapterInterface::class);
        $adapter->expects($this->once())
                ->method('map')
                ->with($source, $destination)
                ->willReturn(false);

        $manager = new MapperManager();
        $this->injectProperty($manager, 'adapters', [$adapter]);

        $this->expectException(MissingMapperException::class);

        $manager->map($source, $destination);
    }
}
