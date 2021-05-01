<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\HttpMessageParser;

use phpDocumentor\Reflection\DocBlock\Tags\Link;
use Slick\JSONAPI\Document;
use Slick\JSONAPI\LinksAwareObject;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Relationship;
use Slick\JSONAPI\Object\Relationships\ToManyRelationship;
use Slick\JSONAPI\Object\Relationships\ToOneRelationship;
use Slick\JSONAPI\Object\ResourceObject;

/**
 * LinksMemberParser
 *
 * @package Slick\JSONAPI\Document\HttpMessageParser
 */
abstract class LinksMemberParser
{

    /**
     * parse
     *
     * @param object $json
     * @param LinksAwareObject|Document $object
     * @return LinksAwareObject|Document|ResourceObject|Relationship|ToManyRelationship|ToOneRelationship
     */
    public static function parse(object $json, LinksAwareObject $object): LinksAwareObject
    {
        $linksData = DocumentParser::getProperty($json, 'links');
        if (!$linksData) {
            return $object;
        }

        $links = new Links();
        foreach ($linksData as $rel => $link) {
            $links->add($rel, is_object($link) ? self::parseLinkObject($link) : $link);
        }

        return $object->withLinks($links);
    }

    private static function parseLinkObject(object $linkData): Link
    {
        return new Link(DocumentParser::getMandatoryProperty($linkData, 'href'));
    }
}