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

    private ?object $instance = null;

    /**
     * Creates a AsResourceObject
     *
     * @param string|null $type
     * @param array|string|null $meta
     * @param array|string|null $links
     * @param string|null $schemaClass
     * @param bool $isCompound
     * @param bool $generateIdentifier
     * @param array|string|null $documentMeta
     * @param array|string|null $documentLinks
     */
    public function __construct(
        private ?string            $type = null,
        private array|string|null  $meta = null,
        private array|string|null  $links = null,
        private ?string            $schemaClass = null,
        private bool               $isCompound = false,
        private bool               $generateIdentifier = true,
        private array|string|null  $documentMeta = null,
        private array|string|null  $documentLinks = null,
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
        $newInstanceWithoutConstructor = $this->class->newInstanceWithoutConstructor();
        $this->withInstance($newInstanceWithoutConstructor);
        return $newInstanceWithoutConstructor;
    }

    /**
     * meta
     *
     * @param array|string|null $meta
     * @return array|null
     */
    public function meta(null|array|string $meta = null): ?array
    {
        if ($meta === null) {
            if (!$this->meta) {
                return null;
            }
            return $this->meta($this->meta);
        }

        if (is_string($meta)) {
            return $this->instance()?->$meta();
        }
        return $meta;
    }

    public function documentMeta(): ?array
    {
        if (!$this->documentMeta) {
            return $this->documentMeta;
        }
        return $this->meta($this->documentMeta);
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

    public function withInstance(object $instance): self
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * AsResourceObject's instance
     * @return object|null
     */
    public function instance(): ?object
    {
        return $this->instance;
    }
}
