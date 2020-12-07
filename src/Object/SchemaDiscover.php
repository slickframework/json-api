<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object;

use Slick\JSONAPI\Exception\DocumentEncoderFailure;

/**
 * SchemaDiscover
 *
 * @package Slick\JSONAPI\Object
 */
interface SchemaDiscover
{

    /**
     * Discovers and creates a schema for provided object
     *
     * @param mixed $object
     * @return ResourceSchema
     * @throws DocumentEncoderFailure if a schema cannot be found or initialized
     */
    public function discover($object): ResourceSchema;
}
