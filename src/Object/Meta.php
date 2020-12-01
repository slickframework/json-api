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
use JsonSerializable;
use Slick\JSONAPI\Exception\InvalidMemberName;
use Slick\JSONAPI\Validator\JsonApiValidator;

/**
 * Meta
 *
 * @package Slick\JSONAPI\Object
 */
final class Meta implements JsonSerializable
{

    /** @var Collection */
    private $data;

    /**
     * Creates a Meta
     *
     * @param array|null $values A key/value pair of meta information
     */
    public function __construct(array $values = [])
    {
        $this->data = new ArrayCollection($values);
    }

    /**
     * Get a given meta entry
     *
     * @param string $name
     * @return mixed|null
     */
    public function get(string $name)
    {
        return $this->data->get($name);
    }

    /**
     * Adds a new entry to the data entries
     *
     * This method will ALWAYS return a new copy (clone) of the Meta object
     * maintaining object immutability.
     *
     * If an array is given as a $nameOrArray then it MUST be an key/value pair
     * of entries to add to this meta information object.
     *
     * @param string|array $nameOrArray
     * @param mixed|null   $value
     *
     * @return Meta
     */
    public function with($nameOrArray, $value = null): Meta
    {
        $copy = clone $this;
        $copy->data = new ArrayCollection($this->data->toArray());

        if (!is_array($nameOrArray)) {
            $copy->data->set($this->validateName($nameOrArray), $value);
            return $copy;
        }

        foreach ($nameOrArray as $key => $value) {
            $copy->data->set($this->validateName($key), $value);
        }
        return $copy;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->data->toArray();
    }

    /**
     * validateName
     *
     * @param string $memberName
     * @return string
     * @throws InvalidMemberName when provided member name does not apply JSON:API member name specification
     */
    private function validateName(string $memberName): string
    {
        if (!JsonApiValidator::instance()->isValid($memberName, JsonApiValidator::VALIDATE_MEMBER_NAME)) {
            throw new InvalidMemberName("'$memberName' is not a valid JSON:API member name.");
        }
        return $memberName;
    }
}
