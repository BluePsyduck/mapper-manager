<?php

declare(strict_types=1);

namespace BluePsyduckTest\MapperManager;

use BluePsyduck\MapperManager\ConfigProvider;
use BluePsyduck\MapperManager\Constant\ConfigKey;
use PHPUnit\Framework\TestCase;

/**
 * The PHPUnit test of the ConfigProvider class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \BluePsyduck\MapperManager\ConfigProvider
 */
class ConfigProviderTest extends TestCase
{
    /**
     * Tests the invoking.
     * @covers ::__invoke
     */
    public function testInvoke(): void
    {
        $configProvider = new ConfigProvider();
        $result = $configProvider();

        $this->assertArrayHasKey('dependencies', $result);
        $this->assertArrayHasKey('factories', $result['dependencies']);
        $this->assertArrayHasKey('invokables', $result['dependencies']);

        $this->assertArrayHasKey(ConfigKey::MAIN, $result);
        $this->assertArrayHasKey(ConfigKey::ADAPTERS, $result[ConfigKey::MAIN]);
        $this->assertArrayHasKey(ConfigKey::MAPPERS, $result[ConfigKey::MAIN]);
    }
}
