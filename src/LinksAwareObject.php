<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI;

use Slick\JSONAPI\Object\Links;

/**
 * LinksAwareObject
 *
 * @package Slick\JSONAPI
 */
interface LinksAwareObject
{

    /**
     * Returns a new document with provided Links object
     *
     * This method will ALWAYS return a new copy (clone) of the document
     * maintaining object immutability.
     *
     * @param Links $links
     * @return LinksAwareObject|self|Document
     */
    public function withLinks(Links $links): self;

    /**
     * A links object related to the primary data.
     *
     * @return Links|null
     */
    public function links(): ?Links;
}
