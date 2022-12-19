<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Attribute;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\DiscoverAttributesMethods;

/**
 * AsResourceObject
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AsResourceObject
{

    use DiscoverAttributesMethods;

    public const LINK_SELF = ResourceSchema::LINK_SELF;
    public const LINK_RELATED = ResourceSchema::LINK_RELATED;

    private ?ReflectionClass $class = null;

    /**
     * Creates a AsResourceObject
     *
     * @param string|null $type
     * @param array|null $meta
     * @param array|null $links
     * @param string|null $schemaClass
     * @param bool $isCompound
     */
    public function __construct(
        private ?string $type = null,
        private ?array  $meta = null,
        private ?array  $links = null,
        private ?string $schemaClass = null,
        private bool    $isCompound = false,
        private bool    $generateIdentifier = true
    ) {
    }

    /**
     * Sets the reflection class to work with
     *
     * @param ReflectionClass $class
     */
    public function withClass(ReflectionClass $class): void
    {
        $this->class = $class;
    }

    /**
     * class
     *
     * @return ReflectionClass|null
     */
    public function class(): ?ReflectionClass
    {
        return $this->class;
    }

    /**
     * Resource type
     *
     * @return string|null
     */
    public function type(): ?string
    {
        if ($this->type) {
            return $this->type;
        }

        if (!$this->class) {
            return null;
        }

        $nameParts = explode("\\", $this->class->getName());
        return $this->fromCamelCase(end($nameParts), '_');
    }

    /**
     * Creates an instance of current reflection class.
     *
     * @return object
     * @throws ReflectionException
     * @throws RuntimeException|DocumentEncoderFailure
     */
    public function createInstance(): object
    {
        if (!$this->class instanceof ReflectionClass) {
            throw new DocumentEncoderFailure(
                "Couldn't create an instance, missing reflection class."
            );
        }
        return $this->class->newInstanceWithoutConstructor();
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
     * schemaClass
     *
     * @return string|null
     */
    public function schemaClass(): ?string
    {
        return $this->schemaClass;
    }

    /**
     * isCompound
     *
     * @return bool
     */
    public function isCompound(): bool
    {
        return $this->isCompound;
    }

    /**
     * generateIdentifier
     *
     * @return bool
     */
    public function generateIdentifier(): bool
    {
        return $this->generateIdentifier;
    }
}
