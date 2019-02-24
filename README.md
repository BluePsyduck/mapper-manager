# Mapper Manager

[![Latest Stable Version](https://poser.pugx.org/bluepsyduck/mapper-manager/v/stable)](https://packagist.org/packages/bluepsyduck/mapper-manager)
[![License](https://poser.pugx.org/bluepsyduck/mapper-manager/license)](https://packagist.org/packages/bluepsyduck/mapper-manager)
[![Build Status](https://travis-ci.com/BluePsyduck/mapper-manager.svg?branch=master)](https://travis-ci.com/BluePsyduck/mapper-manager)
[![codecov](https://codecov.io/gh/BluePsyduck/mapper-manager/branch/master/graph/badge.svg)](https://codecov.io/gh/BluePsyduck/mapper-manager)

The Mapper Manager is, as the name already suggests, a manager of mappers between different types of objects. 

## Mapper

The mapper supports different types of mappers, which have the difference in how they evaluate whether they can map
a certain pair of objects or not. Each type of mapper comes with an interface and a corresponding adapter for the
manager to handle this type of mappers. New types can always be added in the client code if there is a need for them.

If a mapper requires access to the `MapperManager` to e.g. map other objects, you have to implement the 
`MapperManagerAwareInterface` to have it get injected into your mapper instance to avoid having circular dependencies. 

### Static Mapper

A static mapper implementing the `StaticMapperInterface` knows the combination of source and destination object it 
supports based only on the classes, without knowing the actual instances of the objects. The support is evaluated once
when the mapper is added to the manager.

Note: The classes of source and destination must match exactly, the static mapper does not check for any inheritance. 
For example, if there is a class `A` and a class `B extends A`, and the mapper returns `A` as supported class, it won't 
match if an instance of `B` is passed instead. If inheritance must be supported, use a dynamic mapper instead.

It is recommended to always use static mappers if possible, as matching the mapper in the manager is faster with 
static mappers than with dynamic ones.

#### Example

```php
<?php

use BluePsyduck\MapperManager\Mapper\StaticMapperInterface;

class ExampleStaticMapper implements StaticMapperInterface
{
    /**
     * Returns the source class supported by this mapper.
     * @return string
     */
    public function getSupportedSourceClass(): string 
    {
        return DatabaseItem::class;
    }

    /**
     * Returns the destination class supported by this mapper.
     * @return string
     */
    public function getSupportedDestinationClass(): string
    {
        return ResponseItem::class;
    }
    
    /**
     * Maps the source object to the destination one.
     * @param DatabaseItem $source
     * @param ResponseItem $destination
     */
    public function map($source, $destination): void
    {
        $destination->setName($source->getName())
                    ->setDescription($source->getDescription());
    }
}
``` 

### Dynamic Mapper

A dynamic mapper implementing the `DynamicMapperInterface` will decide whether a combination of source and destination
object is supported on the actual instances of the objects. This allows to add additional criteria for support based
on the two involved objects. The support is re-evaluated for each source and destination object passed to the mapper
manager.

#### Example

```php
<?php

use BluePsyduck\MapperManager\Mapper\DynamicMapperInterface;

class ExampleDynamicMapper implements DynamicMapperInterface
{
    /**
     * Returns whether the mapper supports the combination of source and destination object.
     * @param object $source
     * @param object $destination
     * @return bool
     */
    public function supports($source, $destination): bool
    {
        return $source instanceof DatabaseItem::class 
            && $destination instanceof ResponseItem::class
            && $source->getType() === 'public'; // Additional condition not possible with a static mapper.
    }
    
    /**
     * Maps the source object to the destination one.
     * @param DatabaseItem $source
     * @param ResponseItem $destination
     */
    public function map($source, $destination): void
    {
        $destination->setName($source->getName())
                    ->setDescription($source->getDescription());
    }
}
```

## Usage

The usage of the mapper manager is rather straight forward: Create an instance of the `MapperManager` class, add some
adapters, and afterwards add your actual mapper implementations.

```php
<?php

use BluePsyduck\MapperManager\MapperManager;
use BluePsyduck\MapperManager\Adapter\DynamicMapperAdapter;
use BluePsyduck\MapperManager\Adapter\StaticMapperAdapter;

$mapperManager = new MapperManager();

// Add the default adapters included in the library.
$mapperManager->addAdapter(new StaticMapperAdapter());
$mapperManager->addAdapter(new DynamicMapperAdapter());

// Add your actual mappers.
$mapperManager->addMapper(new ExampleStaticMapper());
$mapperManager->addMapper(new ExampleDynamicMapper());

// Actually map objects.
$mapperManager->map($databaseItem, $responseItem);
``` 

### Zend Expressive

When using Zend Expressive, you can add the `ConfigProvider` of the library to your application config and access
the already-initialized mapper manager through the container using `MapperManagerInterface::class` or
`MapperManager::class` as alias.

Add the following config to your project to customize the manager:

```php
<?php

use BluePsyduck\MapperManager\Constant\ConfigKey;

[
    ConfigKey::MAIN => [
        ConfigKey::ADAPTERS => [
            // The aliases of the adapters to add to the manager.
            // The adapters must be accessible through the container with these aliases.
            // The StaticMapperAdapter and DynamicMapperAdapter are added automatically.
        ],
        ConfigKey::MAPPERS => [
            // The aliases of the mappers to add to the container.
            // The mappers must be accessible through the container with these aliases.
        ],
    ],
];
```

Then access the mapper manager through the container:

```php
<?php

use BluePsyduck\MapperManager\MapperManagerInterface;

// Fetch the mapper manager from the container.
$mapperManager = $container->get(MapperManagerInterface::class);

// Actually map objects.
$mapperManager->map($databaseItem, $responseItem);
```
