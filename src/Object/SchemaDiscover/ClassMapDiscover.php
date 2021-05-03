<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\SchemaDiscover;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\ResourceSchema;
use Slick\JSONAPI\Object\SchemaDiscover;

/**
 * ClassMapDiscover
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover
 */
final class ClassMapDiscover implements SchemaDiscover
{

    /**
     * @var Collection
     */
    private $map;

    /**
     * Creates a ClassMapDiscover
     *
     * @param array|null $map
     */
    public function __construct(?array $map = [])
    {
        $this->map = new ArrayCollection($map);
    }

    /**
     * @inheritDoc
     */
    public function discover($object): ResourceSchema
    {
        $key = is_object($object)
            ? get_class($object)
            : (string ) $object;

        if (!$this->isConvertible($key)) {
            throw new DocumentEncoderFailure(
                "Could not create schema for '$key'."
            );
        }

        $className = $this->map->get($key);

        return $this->createSchema($className, $object);
    }

    /**
     * Sets/Overrides a map entry
     *
     * $className can be an array, a callable or a FQCN implementing ResourceSchema
     *
     * @param string $key
     * @param array|string|callable $className
     * @return ClassMapDiscover|SchemaDiscover
     */
    public function map(string $key, $className): SchemaDiscover
    {
        $this->map->set($key, $className);
        return $this;
    }

    /**
     * runCallable
     *
     * @param $object
     * @param callable $className
     * @return ResourceSchema
     */
    private function runCallable($object, callable $className): ResourceSchema
    {
        $result = $className($object);
        if (is_array($result)) {
            return new ArraySchema($result);
        }

        if ($result instanceof ResourceSchema) {
            return $result;
        }

        throw new DocumentEncoderFailure(
            "Object resulting form callable does not implement 'ResourceSchema' or " .
            "is not an array. Could not create resource schema."
        );
    }

    /**
     * createSchema
     *
     * @param $className
     * @param $object
     * @return mixed|ResourceSchema|ArraySchema
     */
    private function createSchema($className, $object)
    {
        if (is_array($className)) {
            return new ArraySchema($className);
        }

        if (is_callable($className)) {
            return $this->runCallable($object, $className);
        }

        if (in_array(ResourceSchema::class, class_implements($className))) {
            return new $className();
        }

        throw new DocumentEncoderFailure(
            "'$className' does not implement ResourceSchema::class. A schema could not be created."
        );
    }

    /**
     * @inheritDoc
     */
    public function isConvertible($object): bool
    {
        $key = is_object($object)
            ? get_class($object)
            : (string ) $object;

        return $this->map->containsKey($key);
    }
}
