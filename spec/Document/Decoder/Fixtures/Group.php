<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Slick\JSONAPI\Document\Decoder\Fixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\Relationship;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceAttribute;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceIdentifier;

/**
 * Group
 *
 * @package spec\Slick\JSONAPI\Document\Decoder\Fixtures
 */
#[AsResourceObject(
    type: "groups",
    meta: ['description' => "a group of members"],
    links: [AsResourceObject::LINK_SELF],
    isCompound: true
)]
final class Group
{

    #[ResourceIdentifier]
    private string $groupId;

    #[Relationship(
        type: Relationship::TO_MANY,
        links: [AsResourceObject::LINK_SELF, AsResourceObject::LINK_RELATED],
        meta: ['description' => "A group can have many members."]
    )]
    private Collection $members;

    public function __construct(
        #[ResourceAttribute]
        private string $name,
        array $members = []
    ) {
        $this->members = new ArrayCollection($members);
        $this->groupId = '639fa6f600925';
    }

    /**
     * groupId
     *
     * @return string
     */
    public function groupId(): string
    {
        return $this->groupId;
    }

    /**
     * members
     *
     * @return Collection
     */
    public function members(): Collection
    {
        return $this->members;
    }

    /**
     * name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}