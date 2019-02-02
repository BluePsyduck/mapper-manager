<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Exception;

use InvalidArgumentException;
use Throwable;

/**
 * The exception thrown when a mapper cannot be assigned to any adapter.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class MissingAdapterException extends InvalidArgumentException implements MapperException
{
    /**
     * Initializes the exception.
     * @param string $mapperClass
     * @param Throwable|null $previous
     */
    public function __construct(string $mapperClass, ?Throwable $previous = null)
    {
        parent::__construct(sprintf(
            'No adapter has been added to handle the mapper %s.',
            $mapperClass
        ), 0, $previous);
    }
}
