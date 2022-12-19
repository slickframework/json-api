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
use Exception;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\Relationship;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceAttribute;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceIdentifier;
use Slick\JSONAPI\Object\SchemaDiscover\AttributeSchema;

/**
 * Post
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
#[AsResourceObject(isCompound: true)]
final class Post
{
    #[ResourceIdentifier(type: 'posts')]
    private string $postId;

    private Collection $comments;

    /**
     * Creates a Post
     *
     * @param User $owner
     * @param string $title
     * @param string|null $body
     *
     * @throws Exception
     */
    public function __construct(
        private User $owner,
        #[ResourceAttribute(required: true)]
        private string $title,
        #[ResourceAttribute]
        private ?string $body = null
    ) {
        $this->postId = AttributeSchema::generateUUID();
        $this->comments = new ArrayCollection();
    }

    /**
     * owner
     *
     * @return User
     */
    #[Relationship(type: Relationship::TO_ONE, name: "author")]
    public function owner(): User
    {
        return $this->owner;
    }

    /**
     * postId
     *
     * @return string
     */
    public function postId(): string
    {
        return $this->postId;
    }

    /**
     * title
     *
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * body
     *
     * @return string|null
     */
    public function body(): ?string
    {
        return $this->body;
    }

    /**
     * comments
     *
     * @return Collection
     */
    public function comments(): Collection
    {
        return $this->comments;
    }
}
