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
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceIdentifier;
use Slick\JSONAPI\Object\SchemaDiscover\AttributeSchema;

/**
 * User
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
#[AsResourceObject(isCompound: true, generateIdentifier: false)]
class User
{
    #[ResourceIdentifier(type: "users")]
    private string $id;

    public function __construct(
        private string $name
    ) {
        $this->id = AttributeSchema::generateUUID();
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
     * name
     *
     * @return string
     */
    #[ResourceAttribute(required: true)]
    public function name(): string
    {
        return $this->name;
    }
}