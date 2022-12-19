<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Attribute;
use ReflectionException;
use Reflector;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover\EncodableAttribute;

/**
 * Relationship
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD)]
class Relationship implements EncodableAttribute
{

    use DiscoverAttributesMethods;

    public const TO_MANY = 'to_many';
    public const TO_ONE  = 'to_one';

    private const LINK_SELF = ResourceSchema::LINK_SELF;
    private const LINK_RELATED = ResourceSchema::LINK_RELATED;

    private ?Reflector $property = null;

    public function __construct(
        private string  $type = self::TO_ONE,
        private ?string $name = null,
        private ?array  $links = null,
        private ?array  $meta = null
    ) {
    }

    /**
     * type
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * name
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->name;
    }

    /**
     * meta
     *
     * @return array|null
     */
    public function meta(): ?array
    {
        return $this->meta;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function retrieveValue(object $encodedObject): mixed
    {
        return $this->type === self::TO_MANY
            ? $this->parseIterable($encodedObject)
            : $this->extractPropertyValue($encodedObject);
    }
}
