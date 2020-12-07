<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Factory;

/**
 * Comment
 *
 * @package Document\Factory
 */
final class Comment
{
    /**
     * @var Person
     */
    private $person;
    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $id;

    /**
     * Creates a Comment
     *
     * @param Person $person
     * @param string $body
     */
    public function __construct(Person $person, string $body)
    {
        $this->id = uniqid('comment_');
        $this->person = $person;
        $this->body = $body;
    }

    /**
     * person
     *
     * @return Person
     */
    public function person(): Person
    {
        return $this->person;
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

    /**
     * id
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }
}