<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use IteratorAggregate;
use Slick\JSONAPI\Object\Resource as ResourceObject;

/**
 * ResourceCollection
 *
 * @package Slick\JSONAPI\Object
 */
final class ResourceCollection implements IteratorAggregate, Resource
{
    /**
     * @var Collection
     */
    private $resources;
    /**
     * @var string
     */
    private $type;

    /**
     * @var ResourceIdentifier
     */
    private $resourceIdentifier;

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
    public function getIterator()
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
     * @inheritDoc
     */
    public function resourceIdentifier(): ResourceIdentifier
    {
        return $this->resourceIdentifier;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->resources->getValues();
    }
}
