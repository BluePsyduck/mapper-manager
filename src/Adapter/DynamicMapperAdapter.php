<?php

declare(strict_types=1);

namespace BluePsyduck\MapperManager\Adapter;

use BluePsyduck\MapperManager\Mapper\DynamicMapperInterface;
use BluePsyduck\MapperManager\Mapper\MapperInterface;

/**
 * The handler for the dynamic mappers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 *
 * @implements MapperAdapterInterface<DynamicMapperInterface<object, object>>
 */
class DynamicMapperAdapter implements MapperAdapterInterface
{
    /** @var array<DynamicMapperInterface<object, object>> */
    protected array $mappers = [];

    public function getHandledMapperInterface(): string
    {
        return DynamicMapperInterface::class;
    }

    public function addMapper(MapperInterface $mapper): void
    {
        $this->mappers[] = $mapper;
    }

    public function map(object $source, object $destination): bool
    {
        foreach ($this->mappers as $mapper) {
            if ($mapper->supports($source, $destination)) {
                $mapper->map($source, $destination);
                return true;
            }
        }
        return false;
    }
}
