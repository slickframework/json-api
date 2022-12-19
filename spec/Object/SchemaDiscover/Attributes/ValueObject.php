<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes;

/**
 * ValueObject
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
final class ValueObject implements \Stringable
{

    /**
     * Creates a ValueObject
     *
     * @param string $value
     */
    public function __construct(private string $value)
    {
        if ($this->value === 'Test fails!') {
            throw new \InvalidArgumentException($this->value);
        }
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->value;
    }
}