<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\SchemaDiscover;

use Exception;
use ReflectionException;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\Relationship;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceIdentifier;
use Slick\JSONAPI\Validator\SchemaDecodeValidator;

/**
 * AttributeSchema
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover
 */
class AttributeSchema implements ResourceSchema
{
    /**
     * Creates a AttributeSchema
     *
     * @param AsResourceObject $asResourceObject
     * @param ResourceIdentifier|null $resourceIdentifier
     * @param array $attributes
     * @param array|null $relationships
     * @param array $relationshipIdentifier
     */
    public function __construct(
        private AsResourceObject $asResourceObject,
        private ?ResourceIdentifier $resourceIdentifier = null,
        private array $attributes = [],
        private ?array $relationships = null,
        private array $relationshipIdentifier = []
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isCompound(): bool
    {
        return $this->asResourceObject->isCompound();
    }

    /**
     * @inheritDoc
     */
    public function type($object): string
    {
        $identifierType = $this->resourceIdentifier?->type();
        if ($identifierType) {
            return $identifierType;
        }
        return $this->asResourceObject->type();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function identifier($object): ?string
    {
        if ($this->resourceIdentifier) {
            return $this->resourceIdentifier->retrieveValue($object);
        }

        return $this->asResourceObject->generateIdentifier()
            ? self::generateUUID()
            : null;
    }

    /**
     * @inheritDoc
     */
    public function attributes($object): ?array
    {
        $attributes = [];
        /**
         * @var string $name
         * @var EncodableAttribute $attribute */
        foreach ($this->attributes as $name => $attribute) {
            $attributes[$name] = $attribute->retrieveValue($object);
        }

        return $attributes;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function relationships($object): ?array
    {
        if (!$this->relationships) {
            return null;
        }

        $relationships = [];
        /**
         * @var string $name
         * @var Relationship $relationship */
        foreach ($this->relationships as $name => $relationship) {
            $value = $relationship->retrieveValue($object);
            if ($relationship->type() === Relationship::TO_ONE && !$value) {
                continue;
            }

            $data = ['data' => $value];
            $this->assignArray($data, 'links', $relationship->links());
            $this->assignArray($data, 'meta', $relationship->meta());

            $relationships[$name] = $data;
        }

        return $relationships;
    }

    /**
     * @inheritDoc
     */
    public function links($object): ?array
    {
        return $this->asResourceObject->links();
    }

    /**
     * @inheritDoc
     */
    public function meta($object): ?array
    {
        return $this->asResourceObject->meta();
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function from($resourceObject)
    {
        $decodedObject = $this->asResourceObject->createInstance();
        $this->resourceIdentifier?->assignValue($decodedObject, $resourceObject);

        $attributes = array_merge($this->attributes, $this->relationshipIdentifier);
        foreach ($attributes as $attribute) {
            $attribute->assignValue($decodedObject, $resourceObject);
        }

        return $decodedObject;
    }

    /**
     * @inheritDoc
     */
    public function validate($resourceObject, SchemaDecodeValidator $validator): void
    {
        $this->resourceIdentifier?->validate($resourceObject, $validator);

        $attributes = array_merge($this->attributes, $this->relationshipIdentifier);
        foreach ($attributes as $attribute) {
            $attribute->validate($resourceObject, $validator);
        }
    }

    /**
     * Generates a UUID string
     *
     * @return string
     * @throws Exception
     */
    public static function generateUUID(): string
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private function assignArray(array &$array, string $key, mixed $value = null): void
    {
        if (!$value) {
            return;
        }

        $array[$key] = $value;
    }
}
