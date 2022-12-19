<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Slick\JSONAPI\Document\Decoder\Fixtures;

use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\Relationship;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceAttribute;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceIdentifier;

/**
 * Member
 *
 * @package spec\Slick\JSONAPI\Document\Decoder\Fixtures
 */
#[AsResourceObject(type: "members", isCompound: true)]
final class Member
{

    #[ResourceIdentifier]
    private string $memberId;

    public function __construct(
        private Group $group,
        #[ResourceAttribute]
        private string $name,
        #[ResourceAttribute]
        private ?int $age = null
    ) {
        $this->memberId = '639fa6f600928';
        $this->group->members()->add($this);
    }

    /**
     * memberId
     *
     * @return string
     */
    public function memberId(): string
    {
        return $this->memberId;
    }

    /**
     * group
     *
     * @return Group
     */
    #[Relationship(type: Relationship::TO_ONE)]
    public function group(): Group
    {
        return $this->group;
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

    /**
     * age
     *
     * @return int|null
     */
    public function age(): ?int
    {
        return $this->age;
    }
}
