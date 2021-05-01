<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\HttpMessageParser;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\MetaAwareObject;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Relationship;
use Slick\JSONAPI\Object\Relationships\ToManyRelationship;
use Slick\JSONAPI\Object\Relationships\ToOneRelationship;
use Slick\JSONAPI\Object\ResourceObject;

/**
 * MetaMemberParser
 *
 * @package Slick\JSONAPI\Document\RequestParser
 */
abstract class MetaMemberParser
{

    /**
     * Parses meta data from a given JSON:API document
     *
     * @param object $json
     * @param MetaAwareObject $document
     * @return Document|ResourceObject|Relationship|ToManyRelationship|ToOneRelationship
     */
    public static function parse(object $json, MetaAwareObject $document): MetaAwareObject
    {
        $metaJson = DocumentParser::getProperty($json, 'meta');
        if (!$metaJson) {
            return $document;
        }
        $data = [];
        foreach ($metaJson as $key => $value) {
            $data[$key] = $value;
        }

        return $document->withMeta(new Meta($data));
    }
}
