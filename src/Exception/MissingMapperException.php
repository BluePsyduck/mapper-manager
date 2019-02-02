<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Exception;

use RuntimeException;
use Throwable;

/**
 * The exception thrown when no matching mapper was found.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class MissingMapperException extends RuntimeException implements MapperException
{
    /**
     * Initializes the exception.
     * @param string $sourceClass
     * @param string $destinationClass
     * @param Throwable|null $previous
     */
    public function __construct(string $sourceClass, string $destinationClass, ?Throwable $previous = null)
    {
        parent::__construct(sprintf(
            'Unable to map %s to %s: No matching mapper.',
            $sourceClass,
            $destinationClass
        ), 0, $previous);
    }
}
