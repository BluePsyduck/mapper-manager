<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Exception;

use InvalidArgumentException;
use Throwable;

/**
 * The exception thrown when an invalid mapper is tried to be added to the manager.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class InvalidMapperException extends InvalidArgumentException implements MapperException
{
    /**
     * Initializes the exception.
     * @param string $mapperClass
     * @param Throwable|null $previous
     */
    public function __construct(string $mapperClass, ?Throwable $previous = null)
    {
        parent::__construct(sprintf(
            'Invalid mapper class: %s. The class must implement one of the derived mapper interfaces.',
            $mapperClass
        ), 0, $previous);
    }
}
