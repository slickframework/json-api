<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Factory;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Post
 *
 * @package Document\Factory
 */
final class Post
{

    private $id;
    /**
     * @var Person
     */
    private $author;
    /**
     * @var string
     */
    private $title;
    /**
     * @var ArrayCollection
     */
    private $comments;

    /**
     * Creates a Post
     *
     * @param Person $author
     * @param string $title
     */
    public function __construct(Person $author, string $title)
    {
        $this->id = uniqid('posts');
        $this->comments = new ArrayCollection();
        $this->author = $author;
        $this->title = $title;
    }

    /**
     * Adds Comment
     *
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment): Post
    {
        $this->comments->add($comment);
        return $this;
    }

    /**
     * id
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * author
     *
     * @return Person
     */
    public function author(): Person
    {
        return $this->author;
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
     * comments
     *
     * @return ArrayCollection
     */
    public function comments(): ArrayCollection
    {
        return $this->comments;
    }
}
