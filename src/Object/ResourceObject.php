<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object;

/**
 * ResourceObject
 *
 * @package Slick\JSONAPI\Object
 */
class ResourceObject implements Resource
{
    /**
     * @var ResourceIdentifier
     */
    private $resourceIdentifier;

    /**
     * @var array|null
     */
    private $attributes;

    /**
     * @var Relationships|null
     */
    private $relationships;

    /**
     * @var Links|null
     */
    private $links;

    /**
     * @var Meta|null
     */
    private $meta;


    /**
     * Creates a ResourceObject
     *
     * @param ResourceIdentifier $resourceIdentifier
     * @param array|null $attributes
     * @param Relationships|null $relationships
     * @param Links|null $links
     * @param Meta|null $meta
     */
    public function __construct(
        ResourceIdentifier $resourceIdentifier,
        ?array $attributes = null,
        ?Relationships $relationships = null,
        ?Links $links = null,
        ?Meta $meta = null
    ) {
        $this->resourceIdentifier = $resourceIdentifier;
        $this->attributes = $attributes;
        $this->links = $links;
        $this->meta = $meta;
        $this->relationships = $relationships;
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        return $this->resourceIdentifier->type();
    }

    /**
     * @inheritDoc
     */
    public function identifier(): ?string
    {
        return $this->resourceIdentifier->identifier();
    }

    /**
     * @inheritDoc
     */
    public function resourceIdentifier(): ResourceIdentifier
    {
        return $this->resourceIdentifier;
    }

    /**
     * attributes
     *
     * @return array|null
     */
    public function attributes(): ?array
    {
        return $this->attributes;
    }

    /**
     * relationships
     *
     * @return Relationships|Relationship[]|null
     */
    public function relationships(): ?Relationships
    {
        return $this->relationships;
    }

    /**
     * links
     *
     * @return Links|null
     */
    public function links(): ?Links
    {
        return $this->links;
    }

    /**
     * meta
     *
     * @return Meta|null
     */
    public function meta(): ?Meta
    {
        return $this->meta;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $data = [
            'type' => $this->type(),
            'id' => $this->identifier()
        ];
        $map = ['attributes', 'links', 'relationships', 'meta'];
        foreach ($map as $property) {
            if (property_exists($this, $property) && $this->$property) {
                $data[$property] = $this->$property;
            }
        }
        return $data;
    }

    /**
     * Returns a new resource object with the given attributes' list
     *
     * This method will ALWAYS return a new copy (clone) of the resource object
     * maintaining object immutability.
     *
     * @param array $attributes
     * @return ResourceObject
     */
    public function withAttributes(array $attributes): ResourceObject
    {
        $copy = clone $this;
        $copy->attributes = $attributes;
        return $copy;
    }

    /**
     * Returns a new resource object with the provided attribute
     *
     * This method will ALWAYS return a new copy (clone) of the resource object
     * maintaining object immutability.
     *
     * @param string $name
     * @param mixed  $value
     * @return ResourceObject
     */
    public function withAttribute(string $name, $value): ResourceObject
    {
        $copy = clone $this;
        $copy->attributes[$name] = $value;
        return $copy;
    }

    /**
     * Returns a new resource object with provided links member
     *
     * This method will ALWAYS return a new copy (clone) of the resource object
     * maintaining object immutability.
     *
     * @param Links $links
     * @return ResourceObject
     */
    public function withLinks(Links $links): ResourceObject
    {
        $copy = clone $this;
        $copy->links = $links;
        return $copy;
    }

    /**
     * Returns a new resource object with provided meta object
     *
     * This method will ALWAYS return a new copy (clone) of the resource object
     * maintaining object immutability.
     *
     * @param Meta $meta
     * @return ResourceObject
     */
    public function withMeta(Meta $meta): ResourceObject
    {
        $copy = clone $this;
        $copy->meta = $meta;
        return $copy;
    }

    /**
     * Returns a new resource object with provided relationships
     *
     * This method will ALWAYS return a new copy (clone) of the resource object
     * maintaining object immutability.
     *
     * @param Relationships $relationships
     * @return ResourceObject
     */
    public function withRelationships(Relationships $relationships): ResourceObject
    {
        $copy = clone $this;
        $copy->relationships = $relationships;
        return $copy;
    }
}
