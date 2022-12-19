<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceAttribute;

/**
 * ExampleResource
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
#[AsResourceObject(type: "example_resources")]
final class ExampleResource
{

    #[ResourceAttribute]
    private string $name = 'Example';

    #[ResourceAttribute]
    private ?string $value = null;

    private ?ValueObject $valueObject = null;

    /**
     * value
     *
     * @return string|null
     */
    public function value(): ?string
    {
        return $this->value;
    }

    /**
     * valueObject
     *
     * @return ValueObject|null
     */
    public function valueObject(): ?ValueObject
    {
        return $this->valueObject;
    }
}