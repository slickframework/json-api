<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Validator\SchemaDecodeValidator;

/**
 * AbstractResourceSchema
 *
 * @package Slick\JSONAPI\Object
 */
abstract class AbstractResourceSchema implements ResourceSchema
{

    /**
     * @inheritDoc
     */
    public function isCompound(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    abstract public function type($object): string;

    /**
     * @inheritDoc
     */
    abstract public function identifier($object): ?string;

    /**
     * @inheritDoc
     */
    public function attributes($object): ?array
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function relationships($object): ?array
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function links($object): ?array
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function meta($object): ?array
    {
        return null;
    }

    /**
     * @inheritDoc
     *
     * @param Resource|Resource[]|array|null $resourceObject
     */
    public function from($resourceObject)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validate($resourceObject, SchemaDecodeValidator $validator): void
    {
        // no validation is performed by default.
    }
}
