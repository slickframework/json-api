<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object;

use ArrayAccess;
use BadMethodCallException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use IteratorAggregate;
use Slick\JSONAPI\Object\Resource as ResourceObject;
use Traversable;

/**
 * ResourceCollection
 *
 * @package Slick\JSONAPI\Object
 */
final class ResourceCollection implements IteratorAggregate, Resource, ArrayAccess
{
    /**
     * @var Collection
     */
    private Collection $resources;
    /**
     * @var string
     */
    private string $type;

    /**
     * @var ResourceIdentifier
     */
    private ResourceIdentifier $resourceIdentifier;

    /**
     * Creates a ResourceCollection
     *
     * @param string $type
     * @param array $resources
     */
    public function __construct(string $type, array $resources = [])
    {
        $this->resources = new ArrayCollection();
        foreach ($resources as $resource) {
            $this->add($resource);
        }
        $this->type = $type;
        $this->resourceIdentifier = new ResourceIdentifier($type);
    }

    /**
     * Adds a resource to the collection
     *
     * @param ResourceObject $resource
     * @return $this
     */
    public function add(ResourceObject $resource): ResourceCollection
    {
        $this->resources->add($resource);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return $this->resources->getIterator();
    }

    /**
     * Check collection emptiness
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->resources->isEmpty();
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function identifier(): ?string
    {
        return null;
    }

    /**
     * first
     *
     * @return false|ResourceObject
     */
    public function first(): bool|Resource
    {
        return $this->resources->first();
    }

    /**
     * @inheritDoc
     */
    public function resourceIdentifier(): ResourceIdentifier
    {
        return $this->resourceIdentifier;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->resources->getValues();
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->resources->offsetExists($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->resources->offsetGet($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new BadMethodCallException(
            "The resource collection array access is read only. " .
            "Used ResourceCollection::add() method instead."
        );
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new BadMethodCallException(
            "The resource collection array access is read only. " .
            "It cannot remove resource objects from it."
        );
    }
}
