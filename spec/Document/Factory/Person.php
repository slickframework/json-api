<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Factory;

/**
 * Person
 *
 * @package Document\Factory
 */
final class Person
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $id;
    /**
     * @var string|null
     */
    private $email;

    /**
     * Creates a Person
     *
     * @param string $name
     * @param string|null $email
     */
    public function __construct(string $name, ?string $email = null)
    {
        $this->name = $name;
        $this->id = uniqid('user');
        $this->email = $email;
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
     * id
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * email
     *
     * @return string|null
     */
    public function email(): ?string
    {
        return $this->email;
    }
}