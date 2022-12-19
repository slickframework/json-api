<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\JSONAPI\Object\SchemaDiscover;

use IteratorAggregate;
use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\ResourceCollectionSchema;

/**
 * AttributeResourceCollectionSchema
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover
 */
class AttributeResourceCollectionSchema extends AttributeSchema implements ResourceCollectionSchema
{
    /**
     * @inheritDoc
     */
    public function attributes($object): ?array
    {
        if ($object instanceof IteratorAggregate) {
            $data = [];
            foreach ($object as $value) {
                $data[] = $value;
            }
            return $data;
        }

        $possibleMethods = ['toArray', 'asArray'];
        foreach ($possibleMethods as $method) {
            if (method_exists($object, $method)) {
                return $object->$method();
            }
        }

        $key = get_class($object);

        throw new DocumentEncoderFailure(
            "Couldn't create a resource collection schema of the resource '$key'. " .
            "Try to implement 'IteratorAggregate' or add a method named '$key::toArray()'."
        );
    }
}
