<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);


namespace Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use ReflectionException;
use ReflectionMethod;
use Reflector;
use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\SchemaDiscover\ReflectorAwareAttribute;
use Stringable;

/**
 * DiscoverAttributesMethods trait
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover
 */
trait DiscoverAttributesMethods
{

    public function documentLinks(): ?array
    {
        if ($this->documentLinks === null) {
            return $this->documentLinks;
        }

        return $this->links($this->documentLinks);
    }

    /**
     * links
     *
     * @return array|null
     */
    public function links(string|iterable|null $definedLinks = null): ?array
    {
        if (!$definedLinks) {
            if (!$this->links) {
                return null;
            }
            return $this->links($this->links);
        }

        if (is_string($definedLinks)) {
            if (!$this->instance()) {
                return null;
            }
            return $this->instance()->$definedLinks();
        }

        if (!is_iterable($definedLinks)) {
            return null;
        }

        $links = [];
        $known = [self::LINK_SELF, self::LINK_RELATED];
        foreach ($definedLinks as $key => $link) {
            if (in_array($link, $known)) {
                $links[$link] = true;
                continue;
            }

            $links[$key] = $link;
        }
        return $links;
    }

    /**
     * @inheritDoc
     */
    public function withProperty(Reflector $property): ReflectorAwareAttribute
    {
        $this->property = $property;
        return $this;
    }



    /**
     * Converts string from camelCase to snake_case
     *
     * @param string $input
     * @param string $separator
     * @return string
     */
    private function fromCamelCase(string $input, string $separator = ''): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode($separator, $ret);
    }

    /**
     * Retrieves a value form provided object property
     *
     * @param object $object
     * @return string|int|bool|float|array|null
     * @throws ReflectionException
     */
    private function getValue(object $object): string|int|bool|float|array|null
    {
        $value = $this->extractPropertyValue($object);

        if (is_scalar($value) || is_array($value) || is_null($value)) {
            return $value;
        }

        if ($value instanceof JsonSerializable) {
            return $value->jsonSerialize();
        }

        if ($value instanceof Stringable) {
            return (string) $value;
        }

        $className = $this->property->getDeclaringClass()->getName();
        $name = $this->property->getName();
        $valueClass = $this->property->getType()->getName();

        throw new DocumentEncoderFailure(
            "Couldn't extract the value of the '$className::$name()' property. ".
            "You should have class '$valueClass' implementing 'Stringable' interface"
        );
    }

    /**
     * Assign a value to a property using reflection
     *
     * @param object $decodedObject
     * @param mixed $value
     * @throws ReflectionException
     */
    private function assignPropertyValue(object $decodedObject, mixed $value): void
    {
        $this->property->setAccessible(true);
        $this->property instanceof ReflectionMethod
            ? $this->property->invoke($decodedObject, $value)
            : $this->property->setValue($decodedObject, $value);
        if ($this->property->isProtected() || $this->property->isPrivate()) {
            $this->property->setAccessible(false);
        }
    }

    /**
     * Extracts property value from provided object
     *
     * @param object $object
     * @return mixed
     * @throws ReflectionException
     */
    private function extractPropertyValue(object $object): mixed
    {
        $this->property->setAccessible(true);
        $value = ($this->property instanceof ReflectionMethod)
            ? $this->property->invoke($object)
            : $this->property->getValue($object);

        if ($this->property->isPrivate() || $this->property->isProtected()) {
            $this->property->setAccessible(false);
        }
        return $value;
    }

    /**
     * @throws ReflectionException
     */
    private function parseIterable(object $object): mixed
    {
        $value = $this->extractPropertyValue($object);

        if (!is_object($value)) {
            return $value;
        }

        if ($value instanceof Collection) {
            return $value->toArray();
        }

        if (is_iterable($value)) {
            $data = [];
            foreach ($value as $item) {
                $data[] = $item;
            }
            return $data;
        }

        $methods = ['toArray', 'asArray'];
        foreach ($methods as $method) {
            if (method_exists($object, $method)) {
                return $object->$method();
            }
        }

        return $value;
    }
}
