<?php

declare(strict_types=1);

namespace BluePsyduckTest\MapperManager\Adapter;

use BluePsyduck\MapperManager\Adapter\DynamicMapperAdapter;
use BluePsyduck\MapperManager\Mapper\DynamicMapperInterface;
use BluePsyduck\TestHelper\ReflectionTrait;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use stdClass;

/**
 * The PHPUnit test of the DynamicMapperAdapter class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \BluePsyduck\MapperManager\Adapter\DynamicMapperAdapter
 */
class DynamicMapperAdapterTest extends TestCase
{
    use ReflectionTrait;

    /**
     * Tests the getHandledMapperInterface method.
     * @covers ::getHandledMapperInterface
     */
    public function testGetHandledMapperInterface(): void
    {
        $adapter = new DynamicMapperAdapter();
        $result = $adapter->getHandledMapperInterface();

        $this->assertEquals(DynamicMapperInterface::class, $result);
    }

    /**
     * Tests the addMapper method.
     * @throws ReflectionException
     * @covers ::addMapper
     */
    public function testAddMapper(): void
    {
        $mapper1 = $this->createMock(DynamicMapperInterface::class);
        $mapper2 = $this->createMock(DynamicMapperInterface::class);

        $adapter = new DynamicMapperAdapter();
        $this->injectProperty($adapter, 'mappers', [$mapper1]);

        $adapter->addMapper($mapper2);
        $this->assertSame([$mapper1, $mapper2], $this->extractProperty($adapter, 'mappers'));
    }

    /**
     * Tests the map method with a supported mapper.
     * @throws ReflectionException
     * @covers ::map
     */
    public function testMapWithSupportedMapper(): void
    {
        $source = new stdClass();
        $destination = new stdClass();

        $mapper1 = $this->createMock(DynamicMapperInterface::class);
        $mapper1->expects($this->once())
                ->method('supports')
                ->with($this->identicalTo($source), $this->identicalTo($destination))
                ->willReturn(false);
        $mapper1->expects($this->never())
                ->method('map');

        $mapper2 = $this->createMock(DynamicMapperInterface::class);
        $mapper2->expects($this->once())
                ->method('supports')
                ->with($this->identicalTo($source), $this->identicalTo($destination))
                ->willReturn(true);
        $mapper2->expects($this->once())
                ->method('map')
                ->with($this->identicalTo($source), $this->identicalTo($destination));

        $mapper3 = $this->createMock(DynamicMapperInterface::class);
        $mapper3->expects($this->never())
                ->method('supports');
        $mapper3->expects($this->never())
                ->method('map');

        $adapter = new DynamicMapperAdapter();
        $this->injectProperty($adapter, 'mappers', [$mapper1, $mapper2, $mapper3]);

        $result = $adapter->map($source, $destination);
        $this->assertTrue($result);
    }

    /**
     * Tests the map method without a supported mapper.
     * @throws ReflectionException
     * @covers ::map
     */
    public function testMapWithoutSupportedMapper(): void
    {
        $source = new stdClass();
        $destination = new stdClass();

        $mapper = $this->createMock(DynamicMapperInterface::class);
        $mapper->expects($this->once())
               ->method('supports')
               ->with($this->identicalTo($source), $this->identicalTo($destination))
               ->willReturn(false);
        $mapper->expects($this->never())
               ->method('map');

        $adapter = new DynamicMapperAdapter();
        $this->injectProperty($adapter, 'mappers', [$mapper]);

        $result = $adapter->map($source, $destination);
        $this->assertFalse($result);
    }
}
