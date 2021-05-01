<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\HttpMessageParser;

use Slick\JSONAPI\Exception\InvalidResourceProperty;

/**
 * DocumentParser
 *
 * @package Slick\JSONAPI\Document\RequestParser
 */
abstract class DocumentParser
{

    /**
     * Get value from json
     *
     * @param Object $json
     * @param string $property
     * @param null $default
     * @return mixed|null
     */
    public static function getProperty(Object $json, string $property, $default = null)
    {
        return property_exists($json, $property) ? $json->$property : $default;
    }

    /**
     * Gets Mandatory Property
     *
     * @param Object $json
     * @param string $property
     * @param string|null $prefix
     *
     * @return mixed
     *
     * @throws InvalidResourceProperty when property does not exists
     */
    public static function getMandatoryProperty(Object $json, string $property, ?string $prefix = '')
    {
        $data = self::getProperty($json, $property);
        if ($data !== null) {
            return $data;
        }

        throw InvalidResourceProperty::error(
            "Missing required document member",
            "The property '{$property}' is a mandatory member in JSON:API document.",
            "{$prefix}",
            $property
        );
    }
}
