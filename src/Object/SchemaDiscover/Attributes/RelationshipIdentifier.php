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
use ReflectionException;
use Reflector;
use Slick\JSONAPI\Object\ErrorObject\ErrorSource;
use Slick\JSONAPI\Object\Relationship as JSONAPIRelationship;
use Slick\JSONAPI\Object\ResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover\DecodableAttribute;
use Slick\JSONAPI\Validator\SchemaDecodeValidator;
use Throwable;

/**
 * RelationshipIdentifier
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD)]
class RelationshipIdentifier implements DecodableAttribute
{

    use DiscoverAttributesMethods;

    private ?Reflector $property = null;

    /**
     * Creates a RelationshipIdentifier
     *
     * @param string $name
     * @param string|null $className
     * @param string|null $type
     */
    public function __construct(
        private string  $name,
        private ?string $className = null,
        private ?string $type = null,
    ) {
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
     * className
     *
     * @return string|null
     */
    public function className(): ?string
    {
        return $this->className;
    }

    /**
     * type
     *
     * @return string|null
     */
    public function type(): ?string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function assignValue(object $decodedObject, ResourceObject $resourceObject): void
    {
        $rawValue = $resourceObject->relationships()->get($this->name)->data()->identifier();
        $className = $this->className;
        $value = $className ? new $className($rawValue) : $rawValue;
        $this->assignPropertyValue($decodedObject, $value);
    }

    /**
     * @inheritDoc
     */
    public function validate(ResourceObject $resourceObject, SchemaDecodeValidator $validator): void
    {
        $name = $this->name;
        $relationShip = $this->checkRelationshipExists($resourceObject, $name, $validator);
        if (!$relationShip) {
            return;
        }

        if (!$this->checkTypeMatch($relationShip, $validator, $name)) {
            return;
        }

        if (!$this->className) {
            return;
        }

        $this->validateObjectCreation($relationShip, $validator, $name);
    }


    /**
     * Tries to create the value object of the class name given with document relationship identifier value
     *
     * @param JSONAPIRelationship $relationShip
     * @param SchemaDecodeValidator $validator
     * @param string $name
     */
    private function validateObjectCreation(
        JSONAPIRelationship $relationShip,
        SchemaDecodeValidator $validator,
        string $name
    ): void {
        try {
            new ($this->className)($relationShip->data()->identifier());
        } catch (InvalidArgumentException $e) {
            $validator->add(
                title: "Relationship '$name' identifier is invalid",
                detail: $e->getMessage(),
                source: new ErrorSource("/data/relationships/$name/data/id"),
                status: "400"
            );
            return;
        } catch (Throwable $e) {
            $validator->add(
                title: "Couldn't create value from relationship '$name' identifier",
                detail: $e->getMessage(),
                source: new ErrorSource("/data/relationships/$name/data/id"),
                status: "500"
            );
        }
    }

    /**
     * Check if the relationship identified by provided name exists.
     *
     * @param ResourceObject $resourceObject
     * @param string $name
     * @param SchemaDecodeValidator $validator
     * @return JSONAPIRelationship|null
     */
    private function checkRelationshipExists(
        ResourceObject $resourceObject,
        string $name,
        SchemaDecodeValidator $validator
    ): ?JSONAPIRelationship {
        $relationShip = $resourceObject->relationships()->get($name);
        if (!$relationShip) {
            $validator->add(
                title: "Missing '$name' relationship",
                detail: "Relationship '$name' is mandatory, but it isn't present in the requested resource object.",
                source: new ErrorSource("/data/relationships/$name"),
                status: "400"
            );
        }
        return $relationShip;
    }

    /**
     * Checks relationship type match
     *
     * @param JSONAPIRelationship $relationShip
     * @param SchemaDecodeValidator $validator
     * @param string $name
     * @return bool
     */
    private function checkTypeMatch(
        JSONAPIRelationship $relationShip,
        SchemaDecodeValidator $validator,
        string $name
    ): bool {
        $type = $this->type;
        $dataType = $relationShip->data()->type();
        if ($type && $type !== $dataType) {
            $validator->add(
                title: "'$name' relationship type mismatch",
                detail: "Relationship '$name' should have type '$type', but got '$dataType' instead.",
                source: new ErrorSource("/data/relationships/$name/data/type"),
                status: "400"
            );
            return false;
        }

        return true;
    }
}
