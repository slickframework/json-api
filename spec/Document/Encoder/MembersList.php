<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace spec\Slick\JSONAPI\Document\Encoder;

use Doctrine\Common\Collections\ArrayCollection;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceCollection;
use Traversable;

/**
 * MembersList
 *
 * @package spec\Slick\JSONAPI\Document\Encoder
 */
#[AsResourceCollection(type: "members")]
final class MembersList implements \IteratorAggregate
{

    public function __construct(private array $members = [])
    {
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayCollection($this->members);
    }
}