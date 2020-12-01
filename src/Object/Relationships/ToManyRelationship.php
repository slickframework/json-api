<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\Relationships;

use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Relationship;
use Slick\JSONAPI\Object\Resource;
use Slick\JSONAPI\Object\ResourceCollection;
use Slick\JSONAPI\Object\ResourceIdentifier;
use Traversable;

/**
 * ToManyRelationship
 *
 * @package Slick\JSONAPI\Object\Relationships
 */
final class ToManyRelationship implements Relationship
{

    use RelationshipMethods;

    /**
     * @var ResourceCollection|Resource[]
     */
    private $resourceCollection;

    /**
     * Creates a ToManyRelationship
     *
     * @param ResourceCollection $resourceCollection
     * @param Links|null $links
     * @param Meta|null $meta
     */
    public function __construct(ResourceCollection $resourceCollection, ?Links $links = null, ?Meta $meta = null)
    {
        $this->links = $links;
        $this->meta = $meta;
        $this->resourceCollection = $resourceCollection;
    }

    /**
     * @inheritDoc
     * @return Resource
     */
    public function data(): Traversable
    {
        return $this->resourceCollection;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $data = $this->links ? ['links' => $this->links] : [];
        $data = !$this->resourceCollection->isEmpty()
            ? array_merge($data, ['data' => $this->dataCollection()])
            : array_merge($data, ['data' => []])
        ;
        return $this->meta ? array_merge($data, ['meta' => $this->meta]) : $data;
    }

    /**
     * data collection
     *
     * @return ResourceIdentifier[]
     */
    private function dataCollection(): array
    {
        $data = [];
        foreach ($this->resourceCollection as $item) {
            $data[] = $item->resourceIdentifier();
        }
        return $data;
    }
}
