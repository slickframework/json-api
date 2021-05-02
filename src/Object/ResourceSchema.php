<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Exception\InvalidResourceDocument;
use Slick\JSONAPI\Validator\SchemaDecodeValidator;

/**
 * ResourceSchema
 *
 * @package Slick\JSONAPI\Object
 */
interface ResourceSchema
{

    const LINK_SELF = 'self';
    const LINK_RELATED = 'related';

    /**
     * Should output a compound resource document
     *
     * @return bool
     */
    public function isCompound(): bool;

    /**
     * Resource type
     *
     * @param mixed $object
     * @return string
     */
    public function type($object): string;

    /**
     * Resource identifier
     *
     * @param mixed $object
     * @return string|null
     */
    public function identifier($object): ?string;

    /**
     * Resource attributes
     *
     * @param mixed $object
     * @return array|null
     */
    public function attributes($object): ?array;

    /**
     * Resource relationships
     *
     * This is a key/value pair of relationships of current object
     * being mapped. This MUST point to the related object and not to
     * the schema that defines it. Schema discovery SHOULD not be part
     * of resource schemas.
     *
     * @param mixed $object
     * @return array|null
     */
    public function relationships($object): ?array;

    /**
     * Resource links
     *
     * This is an key/value pair of links related to the resource object. As a
     * special case you can set self::LINK_SELF => true/false or
     * self::LINK_RELATED => true/false to have links be generated for current
     * object resource.
     *
     * @param mixed $object
     * @return array|null
     */
    public function links($object): ?array;

    /**
     * Resource meta information
     *
     * @param mixed $object
     * @return array|null
     */
    public function meta($object): ?array;

    /**
     * Creates a resource from provided JSON:API document
     *
     * @param ResourceObject|ResourceObject[]|array|null $resourceObject
     * @return mixed
     */
    public function from($resourceObject);

    /**
     * Validates a given document
     *
     * @param ResourceObject|ResourceObject[]|array|null $resourceObject
     */
    public function validate($resourceObject, SchemaDecodeValidator $validator): void;
}
