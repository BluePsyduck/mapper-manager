<?php

declare(strict_types=1);

namespace BluePsyduckTest\MapperManager;

use BluePsyduck\MapperManager\MapperManagerAwareTrait;
use BluePsyduck\MapperManager\MapperManagerInterface;
use BluePsyduck\TestHelper\ReflectionTrait;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * The PHPUnit test of the MapperManagerAwareTrait class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \BluePsyduck\MapperManager\MapperManagerAwareTrait
 */
class MapperManagerAwareTraitTest extends TestCase
{
    use ReflectionTrait;

    /**
     * @throws ReflectionException
     * @covers ::setMapperManager
     */
    public function testSetMapperManager(): void
    {
        $mapperManager = $this->createMock(MapperManagerInterface::class);

        $trait = $this->getMockBuilder(MapperManagerAwareTrait::class)
                      ->getMockForTrait();

        $trait->setMapperManager($mapperManager);

        $this->assertSame($mapperManager, $this->extractProperty($trait, 'mapperManager'));
    }
}
