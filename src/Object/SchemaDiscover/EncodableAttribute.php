<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\JSONAPI\Object\SchemaDiscover;

/**
 * EncodableAttribute
 *
 * @package Slick\JSONAPI\Object\SchemaDiscover
 */
interface EncodableAttribute extends ReflectorAwareAttribute
{

    /**
     * Retrieves the attribute value from provided object
     *
     * @param object $encodedObject
     * @return mixed
     */
    public function retrieveValue(object $encodedObject): mixed;
}
