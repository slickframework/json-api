<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\ErrorObject;

use JsonSerializable;
use Slick\JSONAPI\Exception\InvalidObjectCreation;

/**
 * ErrorSource
 *
 * @package Slick\JSONAPI\Object\ErrorObject
 */
final class ErrorSource implements JsonSerializable
{
    /**
     * @var string|null
     */
    private $pointer;

    /**
     * @var string|null
     */
    private $parameter;


    /**
     * Creates a ErrorSource
     *
     * @param string|null $pointer
     * @param string|null $parameter
     */
    public function __construct(?string $pointer = null, ?string $parameter = null)
    {
        if ($pointer === null  && $parameter === null) {
            throw new InvalidObjectCreation(
                "Cannot create error source object without a pointer or a parameter."
            );
        }
        $this->pointer = $pointer;
        $this->parameter = $parameter;
    }

    /**
     * pointer
     *
     * @return string|null
     */
    public function pointer(): ?string
    {
        return $this->pointer;
    }

    /**
     * parameter
     *
     * @return string|null
     */
    public function parameter(): ?string
    {
        return $this->parameter;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        $data = $this->pointer ? ['pointer' => $this->pointer] : [];
        return $this->parameter ? array_merge($data, ['parameter' => $this->parameter]) : $data;
    }
}
