<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Attribute;
use InvalidArgumentException;
use ReflectionProperty;
use Reflector;
use Slick\JSONAPI\Object\ErrorObject\ErrorSource;
use Slick\JSONAPI\Object\ResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover\DecodableAttribute;
use Slick\JSONAPI\Object\SchemaDiscover\EncodableAttribute;
use Slick\JSONAPI\Object\SchemaDiscover\ReflectorAwareAttribute;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\DiscoverAttributesMethods;
use Slick\JSONAPI\Validator\SchemaDecodeValidator;
use Throwable;

/**
 * ResourceAttribute
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD)]
class ResourceAttribute implements DecodableAttribute, EncodableAttribute
{
    use DiscoverAttributesMethods;

    private array $instances = [];

    private Reflector|ReflectionProperty|null $property = null;

    /**
     * Creates a ResourceAttribute
     *
     * @param string|null $name
     * @param string|null $className
     * @param bool $required
     */
    public function __construct(
        private ?string $name = null,
        private ?string $className = null,
        private bool $required = false,
        private ?string $factory = null,
        private ?string $getter = null,
        private ?string $format = null
    ) {
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
     * Assigns a value from the JSON API resource object to the object being decoded
     *
     * @param object $decodedObject
     * @param ResourceObject $resourceObject
     */
    public function assignValue(object $decodedObject, ResourceObject $resourceObject): void
    {
        $attributes = $resourceObject->attributes();
        $name = $this->name ?: $this->property->getName();
        if (!array_key_exists($name, $attributes)) {
            return;
        }

        $rawValue = $attributes[$name];
        $className = $this->className;
        $value = $className && $rawValue ? $this->createObject($className, $rawValue, $name) : $rawValue;

        $this->assignPropertyValue($decodedObject, $value);
    }

    /**
     * @inheritDoc
     */
    public function validate(ResourceObject $resourceObject, SchemaDecodeValidator $validator): void
    {
        $name = $this->name ?: $this->property->getName();

        if (!$this->checkRequired($name, $resourceObject, $validator)) {
            return;
        }

        if (!$this->hasAttribute($name, $resourceObject)) {
            return;
        }

        if (!$this->className || !$resourceObject->attributes()[$name]) {
            return;
        }

        $this->verifyObjectCreation($name, $resourceObject, $validator);
    }

    /**
     * @inheritDoc
     */
    public function retrieveValue(object $encodedObject): mixed
    {
        return $this->getValue($encodedObject);
    }

    /**
     * Checks if a required attribute exists
     *
     * @param string $name
     * @param ResourceObject $resourceObject
     * @param SchemaDecodeValidator $validator
     * @return bool
     */
    private function checkRequired(string $name, ResourceObject $resourceObject, SchemaDecodeValidator $validator): bool
    {
        if ($this->required && !$this->hasAttribute($name, $resourceObject)) {
            $validator->add(
                title: "Missing '$name' attribute",
                detail: "Attribute '$name' is mandatory, but it isn't present in the requested resource object.",
                source: new ErrorSource("/data/attributes/$name"),
                status: "400"
            );
            return false;
        }

        return true;
    }

    /**
     * Check if resource object has a given attribute.
     *
     * @param string $name
     * @param ResourceObject $resourceObject
     * @return bool
     */
    private function hasAttribute(string $name, ResourceObject $resourceObject): bool
    {
        return $resourceObject->attributes() && array_key_exists($name, $resourceObject->attributes());
    }

    /**
     * Retrieves the attribute with given name from provided resource object
     *
     * @param string $name
     * @param ResourceObject $resourceObject
     * @return mixed
     */
    private function attribute(string $name, ResourceObject $resourceObject): mixed
    {
        if (!$this->hasAttribute($name, $resourceObject)) {
            return null;
        }

        return $resourceObject->attributes()[$name];
    }


    /**
     * Verifies if it's possible to create the attribute value with a custom class
     *
     * @param string $name
     * @param ResourceObject $resourceObject
     * @param SchemaDecodeValidator $validator
     */
    private function verifyObjectCreation(
        string $name,
        ResourceObject $resourceObject,
        SchemaDecodeValidator $validator
    ): void {
        try {
            $this->createObject($this->className, $this->attribute($name, $resourceObject), $name);
        } catch (InvalidArgumentException $e) {
            $validator->add(
                title: "Attribute '$name' is invalid",
                detail: $e->getMessage(),
                source: new ErrorSource("/data/attributes/$name"),
                status: "400"
            );
            return;
        } catch (Throwable $e) {
            $validator->add(
                title: "Couldn't create value from attribute '$name'",
                detail: $e->getMessage(),
                source: new ErrorSource("/data/attributes/$name"),
                status: "500"
            );
        }
    }

    private function createObject(string $className, mixed $value, string $attribute): object
    {
        $key = "$className::$attribute";
        if (array_key_exists($key, $this->instances)) {
            return $this->instances[$key];
        }

        if ($this->factory) {
            return $this->createFromFactory($key, $className, $value);
        }

        if (enum_exists($className)) {
            return $this->createEnum($key, $className, $value);
        }

        return $this->createObjectInstance($key, $className, $value);
    }

    /**
     * Creates an object from a factory method
     *
     * @param string $key
     * @param string $className
     * @param mixed $value
     * @return object
     */
    private function createFromFactory(string $key, string $className, mixed $value): object
    {
        $object = call_user_func_array([$className, $this->factory], [$value]);
        $this->instances[$key] = $object;
        return $object;
    }

    /**
     * Creates an object instance
     *
     * @param string $key
     * @param string $className
     * @param mixed $value
     * @return mixed
     */
    private function createObjectInstance(string $key, string $className, mixed $value): object
    {
        $object = new ($className)($value);
        $this->instances[$key] = $object;
        return $object;
    }

    /**
     * Creates an enum with provided value
     *
     * @param string $key
     * @param string $className
     * @param mixed $value
     * @return \BackedEnum
     */
    private function createEnum(string $key, string $className, mixed $value): \BackedEnum
    {
        $enum = ($className)::tryFrom($value);
        if (!$enum) {
            throw new InvalidArgumentException("Unkown value for $value");
        }
        $this->instances[$key] = $enum;
        return $enum;
    }
}
