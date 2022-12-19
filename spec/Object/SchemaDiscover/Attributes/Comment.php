<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Slick\JSONAPI\Object\SchemaDiscover\AttributeSchema;

/**
 * Comment
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
final class Comment
{

    private string $commentId;

    public function __construct(
        private Post $post,
        private User $author,
        private string $body
    ) {
        $this->commentId = AttributeSchema::generateUUID();
    }

    /**
     * commentId
     *
     * @return string
     */
    public function commentId(): string
    {
        return $this->commentId;
    }

    /**
     * post
     *
     * @return Post
     */
    public function post(): Post
    {
        return $this->post;
    }

    /**
     * author
     *
     * @return User
     */
    public function author(): User
    {
        return $this->author;
    }

    /**
     * body
     *
     * @return string
     */
    public function body(): string
    {
        return $this->body;
    }
}