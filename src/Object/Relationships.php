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
use JsonSerializable;
use Traversable;

/**
 * Relationships
 *
 * @package Slick\JSONAPI\Object
 */
final class Relationships implements IteratorAggregate, JsonSerializable
{
    /**
     * @var Collection
     */
    private $relationships;

    /**
     * Creates a Relationships
     *
     * @param array $relationships
     */
    public function __construct(array $relationships = [])
    {
        $this->relationships = new ArrayCollection();
        foreach ($relationships as $name => $relationship) {
            $this->add($name, $relationship);
        }
    }

    /**
     * Adds a relations to the collection
     *
     * @param string $name
     * @param Relationship $relationship
     * @return $this
     */
    public function add(string $name, Relationship $relationship): Relationships
    {
        $this->relationships->set($name, $relationship);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        $data = [];
        foreach ($this->relationships as $name => $relationship) {
            $data[$name] = $relationship;
        }
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return $this->relationships->getIterator();
    }

    /**
     * Check if relations list is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->relationships->isEmpty();
    }

    /**
     * Gets the relationship stored under provided name
     *
     * @param string $name
     * @return Relationship|null
     */
    public function get(string $name): ?Relationship
    {
        return $this->relationships->get($name);
    }
}
