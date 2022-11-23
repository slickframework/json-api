<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document;

use Psr\Http\Message\MessageInterface;
use Slick\JSONAPI\Document;
use Slick\JSONAPI\Document\HttpMessageParser\DocumentParser;
use Slick\JSONAPI\Document\HttpMessageParser\JsonApiMemberParser;
use Slick\JSONAPI\Document\HttpMessageParser\LinksMemberParser;
use Slick\JSONAPI\Document\HttpMessageParser\MetaMemberParser;
use Slick\JSONAPI\Document\HttpMessageParser\ResourceObjectParser;

/**
 * RequestParser
 *
 * @package Slick\JSONAPI\Document
 */
final class HttpMessageParser
{

    /**
     * Parses provided HTTP message into a JSON:API document
     *
     * @param MessageInterface $message
     * @return Document
     */
    public function parse(MessageInterface $message): Document
    {
        $message->getBody()->rewind();
        $json = json_decode($message->getBody()->getContents());
        $data = DocumentParser::getMandatoryProperty($json, 'data');
        $document =  new ResourceDocument((new ResourceObjectParser($data))->parse());

        $document = JsonApiMemberParser::parse($json, $document);
        $document = MetaMemberParser::parse($json, $document);
        return LinksMemberParser::parse($json, $document);
    }
}
