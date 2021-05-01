<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document\HttpMessageParser;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use Slick\Http\Message\Uri;
use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\ResourceDocument;
use Slick\JSONAPI\Exception\InvalidResourceProperty;
use Slick\JSONAPI\Exception\UnsupportedFeature;
use Slick\JSONAPI\Exception\UnsupportedJsonApiVersion;
use Slick\JSONAPI\JsonApi;

/**
 * JsonApiMemberParser
 *
 * @package Slick\JSONAPI\Document\RequestParser
 */
abstract class JsonApiMemberParser
{

    /**
     * Parse JsonAPI
     *
     * @param object $json
     * @param ResourceDocument $document
     * @return Document
     */
    public static function parse(object $json, Document $document): Document
    {
        $json = DocumentParser::getProperty($json, 'jsonapi');
        if (!$json) {
            return $document;
        }

        try {
            $version = DocumentParser::getProperty($json, 'version');
            $jsonApi = new JsonApi($version);
            $jsonApi = self::parseJsonApiExt($json, $jsonApi);
            $jsonApi = self::parseJsonApiProfiles($json, $jsonApi);
            $jsonApi = MetaMemberParser::parse($json, $jsonApi);
        } catch (UnsupportedFeature $unsupportedFeature) {
            throw InvalidResourceProperty::error(
                "JSON:API unsupported feature",
                "The information present on 'jsonapi' member is not supported by specified version. ".
                $unsupportedFeature->getMessage(),
                "/jsonapi",
                "version",
                0,
                $unsupportedFeature
            );
        } catch (UnsupportedJsonApiVersion $unsupportedVersion) {
            throw InvalidResourceProperty::error(
                "JSON:API unsupported version",
                "Couldn't create JSON:API document: ".
                $unsupportedVersion->getMessage(),
                "/jsonapi",
                "version",
                0,
                $unsupportedVersion
            );
        }

        $document = $document->withJsonapi($jsonApi);
        return $document;
    }

    /**
     * parseJsonApiExt
     *
     * @param $json
     * @param JsonApi $jsonApi
     * @return JsonApi
     */
    private static function parseJsonApiExt($json, JsonApi $jsonApi): JsonApi
    {
        $ext = DocumentParser::getProperty($json, 'ext');
        if (!$ext) {
            return $jsonApi;
        }

        try {
            return $jsonApi->withExtensions(self::createUriList($ext));
        } catch (InvalidArgumentException $ex) {
            throw InvalidResourceProperty::error(
                "Error parsing extension link",
                $ex->getMessage(),
                "/jsonapi",
                "ext",
                0,
                $ex
            );
        }
    }

    /**
     * parseJsonApiProfiles
     *
     * @param $json
     * @param JsonApi $jsonApi
     * @return JsonApi
     */
    private static function parseJsonApiProfiles($json, JsonApi $jsonApi): JsonApi
    {
        $profile = DocumentParser::getProperty($json, 'profile');
        if (!$profile) {
            return $jsonApi;
        }

        try {
            return $jsonApi->withProfiles(self::createUriList($profile));
        } catch (InvalidArgumentException $ex) {
            throw InvalidResourceProperty::error(
                "Error parsing profile link",
                $ex->getMessage(),
                "/jsonapi",
                "profile",
                0,
                $ex
            );
        }
    }

    /**
     * Creates an URI List from provided items list
     *
     * @param array $items
     * @return UriInterface[]
     */
    private static function createUriList(array $items): array
    {
        $list = [];
        foreach ($items as $item) {
            $list[] = new Uri($item);
        }
        return $list;
    }
}
