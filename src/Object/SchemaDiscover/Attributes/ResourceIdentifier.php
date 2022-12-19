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
 * ResourceIdentifier
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD)]
class ResourceIdentifier implements EncodableAttribute, DecodableAttribute
{

    use DiscoverAttributesMethods;

    private Reflector|ReflectionProperty|null $property = null;

    /**
     * Creates a ResourceIdentifier
     *
     * @param string|null $type
     * @param string|null $name
     * @param string|null $className
     * @param bool $required
     */
    public function __construct(
        private ?string $type = null,
        private ?string $className = null,
        private bool $required = false
    ) {
    }

    /**
     * Resource type
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
    public function retrieveValue(object $encodedObject): mixed
    {
        return $this->extractPropertyValue($encodedObject);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function assignValue(object $decodedObject, ResourceObject $resourceObject): void
    {
        $identifier = $resourceObject->resourceIdentifier()->identifier();

        $className = $this->className;
        $value = $className ? new $className($identifier) : $identifier;

        $this->assignPropertyValue($decodedObject, $value);
    }

    /**
     * @inheritDoc
     */
    public function validate(ResourceObject $resourceObject, SchemaDecodeValidator $validator): void
    {
        if (!$this->checkRequired($resourceObject, $validator)) {
            return;
        }

        if (!$resourceObject->resourceIdentifier()->identifier()) {
            return;
        }

        if (!$this->className) {
            return;
        }

        $this->verifyObjectCreation($resourceObject, $validator);
    }

    private function checkRequired(ResourceObject $resourceObject, SchemaDecodeValidator $validator): bool
    {
        if ($this->required && !$resourceObject->resourceIdentifier()->identifier()) {
            $validator->add(
                title: "Missing resource identifier",
                detail: "The resource identifier is mandatory, but it isn't present in the requested resource object.",
                source: new ErrorSource("/data/id"),
                status: "400"
            );
            return false;
        }

        return true;
    }

    private function verifyObjectCreation(ResourceObject $resourceObject, SchemaDecodeValidator $validator)
    {
        try {
            new ($this->className)($resourceObject->resourceIdentifier()->identifier());
        } catch (InvalidArgumentException $e) {
            $validator->add(
                title: "Resource identifier is invalid",
                detail: $e->getMessage(),
                source: new ErrorSource("/data/id"),
                status: "400"
            );
            return;
        } catch (Throwable $e) {
            $validator->add(
                title: "Couldn't create value from resource identifier",
                detail: $e->getMessage(),
                source: new ErrorSource("/data/id"),
                status: "500"
            );
        }
    }
}
