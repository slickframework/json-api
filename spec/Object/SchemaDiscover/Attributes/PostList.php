<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use IteratorAggregate;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceCollection;
use Traversable;

/**
 * PostList
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
#[AsResourceCollection(type: 'posts')]
final class PostList implements IteratorAggregate
{

    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * posts
     *
     * @return Collection
     */
    public function posts(): Collection
    {
        return $this->posts;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): ArrayCollection
    {
        return $this->posts;
    }
}