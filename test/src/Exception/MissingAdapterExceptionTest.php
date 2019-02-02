<?php

declare(strict_types=1);

namespace BluePsyduckTest\MapperManager\Exception;

use BluePsyduck\MapperManager\Exception\MissingAdapterException;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * The PHPUnit test of the MissingAdapterException class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \BluePsyduck\MapperManager\Exception\MissingAdapterException
 */
class MissingAdapterExceptionTest extends TestCase
{
    /**
     * Tests the constructing.
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $mapperClass = 'foo';
        /* @var Exception&MockObject $previous */
        $previous = $this->createMock(Exception::class);
        $expectedMessage = 'No adapter has been added to handle the mapper foo.';

        $exception = new MissingAdapterException($mapperClass, $previous);

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
