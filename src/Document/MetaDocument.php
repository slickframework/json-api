<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;

/**
 * MetaDocument
 *
 * @package Slick\JSONAPI\Document
 */
final class MetaDocument implements Document
{
    use DocumentMethods;

    /**
     * Creates a MetaDocument
     *
     * @param Meta $meta
     * @param JsonApi|null $jsonapi
     * @param Links|null $links
     */
    public function __construct(Meta $meta, ?JsonApi $jsonapi = null, ?Links $links = null)
    {
        $this->meta = $meta;
        $this->jsonapi = $jsonapi;
        $this->links = $links;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        $data = $this->jsonapi ? ['jsonapi' => $this->jsonapi] : [];
        $data = array_merge($data, ['meta' => $this->meta]);
        return $this->links
            ? array_merge($data, ['links' => $this->links])
            : $data;
    }
}
