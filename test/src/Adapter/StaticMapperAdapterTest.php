<?php

declare(strict_types=1);

namespace BluePsyduckTest\MapperManager\Adapter;

use BluePsyduck\MapperManager\Adapter\StaticMapperAdapter;
use BluePsyduck\MapperManager\Mapper\StaticMapperInterface;
use BluePsyduck\TestHelper\ReflectionTrait;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use stdClass;

/**
 * The PHPUnit test of the StaticMapperAdapter class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \BluePsyduck\MapperManager\Adapter\StaticMapperAdapter
 */
class StaticMapperAdapterTest extends TestCase
{
    use ReflectionTrait;

    /**
     * Tests the getHandledMapperInterface method.
     * @covers ::getHandledMapperInterface
     */
    public function testGetHandledMapperInterface(): void
    {
        $adapter = new StaticMapperAdapter();
        $result = $adapter->getHandledMapperInterface();

        $this->assertEquals(StaticMapperInterface::class, $result);
    }

    /**
     * Tests the addMapper method.
     * @throws ReflectionException
     * @covers ::addMapper
     */
    public function testAddMapper(): void
    {
        $mapper1 = $this->createMock(StaticMapperInterface::class);
        $mapper1->expects($this->once())
                ->method('getSupportedSourceClass')
                ->willReturn('foo');
        $mapper1->expects($this->once())
                ->method('getSupportedDestinationClass')
                ->willReturn('bar');

        $mapper2 = $this->createMock(StaticMapperInterface::class);
        $mapper2->expects($this->once())
                ->method('getSupportedSourceClass')
                ->willReturn('foo');
        $mapper2->expects($this->once())
                ->method('getSupportedDestinationClass')
                ->willReturn('baz');

        $adapter = new StaticMapperAdapter();
        $this->assertSame([], $this->extractProperty($adapter, 'mappers'));

        $adapter->addMapper($mapper1);
        $this->assertSame(['foo' => ['bar' => $mapper1]], $this->extractProperty($adapter, 'mappers'));

        $adapter->addMapper($mapper2);
        $this->assertSame(
            ['foo' => ['bar' => $mapper1, 'baz' => $mapper2]],
            $this->extractProperty($adapter, 'mappers')
        );
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

        $mapper = $this->createMock(StaticMapperInterface::class);
        $mapper->expects($this->once())
               ->method('map')
               ->with($this->identicalTo($source), $this->identicalTo($destination));

        $mappers = [
            'stdClass' => [
                'stdClass' => $mapper,
            ],
        ];

        $adapter = new StaticMapperAdapter();
        $this->injectProperty($adapter, 'mappers', $mappers);

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

        $mapper = $this->createMock(StaticMapperInterface::class);
        $mapper->expects($this->never())
               ->method('map');

        $mappers = [
            'foo' => [
                'bar' => $mapper,
            ],
        ];

        $adapter = new StaticMapperAdapter();
        $this->injectProperty($adapter, 'mappers', $mappers);

        $result = $adapter->map($source, $destination);
        $this->assertFalse($result);
    }
}
