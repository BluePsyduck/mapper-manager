<?php

declare(strict_types=1);

namespace BluePsyduckTest\MapperManager\Exception;

use BluePsyduck\MapperManager\Exception\MissingMapperException;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * The PHPUnit test of the MissingMapperException class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \BluePsyduck\MapperManager\Exception\MissingMapperException
 */
class MissingMapperExceptionTest extends TestCase
{
    /**
     * Tests the constructing.
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $sourceClass = 'foo';
        $destinationClass = 'bar';
        /* @var Exception&MockObject $previous */
        $previous = $this->createMock(Exception::class);
        $expectedMessage = 'No matching mapper can be found to map foo to bar.';

        $exception = new MissingMapperException($sourceClass, $destinationClass, $previous);

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
