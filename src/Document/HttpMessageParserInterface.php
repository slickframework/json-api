<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Slick\JSONAPI\Document;

use Psr\Http\Message\MessageInterface;
use Slick\JSONAPI\Document;

/**
 * HttpMessageParserInterface
 *
 * @package Slick\JSONAPI\Document
 */
interface HttpMessageParserInterface
{

    /**
     * Parses provided HTTP message into a JSON:API document
     *
     * @param MessageInterface $message
     * @return Document
     */
    public function parse(MessageInterface $message): Document;
}
