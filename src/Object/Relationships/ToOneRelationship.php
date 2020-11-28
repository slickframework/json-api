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

/**
 * ToOneRelationship
 *
 * @package Slick\JSONAPI\Object\Relationships
 */
final class ToOneRelationship implements Relationship
{

    use RelationshipMethods;

    /**
     * @var Resource|null
     */
    private $resource;

    /**
     * Creates a ToOneRelationship
     *
     * @param Resource|null $resource
     * @param Links|null $links
     * @param Meta|null $meta
     */
    public function __construct(?Resource $resource = null, ?Links $links = null, ?Meta $meta = null)
    {
        $this->resource = $resource;
        $this->links = $links;
        $this->meta = $meta;
    }

    /**
     * @inheritDoc
     */
    public function data()
    {
        return $this->resource;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $data = $this->links ? ['links' => $this->links] : [];
        $data = $this->resource
            ? array_merge($data, ['data' => $this->resource->resourceIdentifier()])
            : array_merge($data, ['data' => null])
        ;
        return $this->meta ? array_merge($data, ['meta' => $this->meta]) : $data;
    }
}
