<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\SchemaDiscover;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use Reflector;
use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceCollection;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\Relationship;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\RelationshipIdentifier;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceAttribute;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceIdentifier;

/**
 * AttributeSchemaDiscover
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover
 */
final class AttributeSchemaDiscover implements SchemaDiscover
{
    /** @var array<string, ResourceSchema> */
    public array $map = [];

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function discover($object): ResourceSchema
    {
        if (is_array($object)) {
            return new ArraySchema($object);
        }

        $key = is_string($object) ? $object : get_class($object);
        if (array_key_exists($key, $this->map)) {
            return $this->map[$key];
        }

        $schema = $this->createAttributeSchemaFor($object);
        $this->map[$key] = $schema;
        return $schema;
    }

    /**
     * @inheritDoc
     */
    public function map(string $key, $className): SchemaDiscover
    {
        if (is_array($className)) {
            $schema = new ArraySchema($className);
        }

        if (in_array(ResourceSchema::class, class_implements($className))) {
            $schema = new $className();
        }
        $this->map[$key] = $schema;
        return $this;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function isConvertible($object): bool
    {
        if (is_array($object)) {
            return true;
        }

        return $this->hasResourceObjectAttribute($object);
    }


    /**
     * @throws ReflectionException
     */
    private function createAttributeSchemaFor(mixed $object): ResourceSchema
    {
        $key = is_string($object) ? $object : get_class($object);
        $class = new ReflectionClass($object);
        $attributes = array_merge(
            $class->getAttributes(AsResourceObject::class),
            $class->getAttributes(AsResourceCollection::class)
        );

        if (empty($attributes)) {
            throw new DocumentEncoderFailure(
                "Couldn't create a resource schema of the resource '$key'. " .
                "Try to add the attribute 'AsResourceObject' or 'AsResourceCollection' to the '$key' class."
            );
        }

        /** @var AsResourceObject $asResourceObject */
        $asResourceObject = $attributes[0]->newInstance();
        $asResourceObject->withClass($class);

        return $asResourceObject instanceof AsResourceCollection
            ? new AttributeResourceCollectionSchema($asResourceObject)
            : $this->createAttributeSchema($asResourceObject);
    }

    /**
     * @throws ReflectionException
     */
    private function hasResourceObjectAttribute(mixed $object): bool
    {
        $reflection = new ReflectionClass($object);
        $attributes = array_merge(
            $reflection->getAttributes(AsResourceObject::class),
            $reflection->getAttributes(AsResourceCollection::class)
        );
        return !empty($attributes);
    }

    /**
     * @throws ReflectionException
     */
    private function createAttributeSchema(AsResourceObject $asResourceObject): AttributeSchema
    {
        $args = array_merge(compact('asResourceObject'), ['relationshipIdentifier' => [], 'attributes' => []]);
        $properties = array_merge(
            $asResourceObject->class()->getProperties(),
            $asResourceObject->class()->getMethods()
        );

        foreach ($properties as $property) {
            $attributes = $property->getAttributes();
            $args = array_merge_recursive(
                $args,
                $this->parseAttributes($attributes, $property)
            );
        }

        return (new ReflectionClass(AttributeSchema::class))->newInstanceArgs($args);
    }

    /**
     * parseAttributes
     *
     * @param array<ReflectionAttribute> $attributes
     * @param Reflector $reflector
     * @return array
     */
    private function parseAttributes(array $attributes, Reflector $reflector): array
    {
        $result = [];
        foreach ($attributes as $attribute) {
            switch ($attribute->getName()) {
                case ResourceAttribute::class:
                    $instance = $attribute->newInstance();
                    $name = $instance->name() ?: $reflector->getName();
                    $result['attributes'][$name] = $instance->withProperty($reflector);
                    break;

                case ResourceIdentifier::class:
                    $instance = $attribute->newInstance();
                    $result['resourceIdentifier'] = $instance->withProperty($reflector);
                    break;

                case Relationship::class:
                    $instance = $attribute->newInstance();
                    $name = $instance->name() ?: $reflector->getName();
                    $result['relationships'][$name] = $instance->withProperty($reflector);
                    break;

                case RelationshipIdentifier::class:
                    $instance = $attribute->newInstance();
                    $result['relationshipIdentifier'][] = $instance->withProperty($reflector);
            }
        }

        return $result;
    }
}
