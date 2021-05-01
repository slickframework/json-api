<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\LinksAwareObject;
use Slick\JSONAPI\MetaAwareObject;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Resource;

/**
 * DocumentMethods trait
 *
 * @package Slick\JSONAPI\Document
 */
trait DocumentMethods
{

    /**
     * @var JsonApi|null
     */
    protected $jsonapi = null;

    /**
     * @var Meta|null
     */
    protected $meta = null;

    /**
     * @var Links|null
     */
    protected $links = null;

    /**
     * @var Resource|Resource[]|array|null
     */
    protected $data;

    /**
     * @var Resource[]|null
     */
    public $included;

    /**
     * @inheritDoc
     */
    public function jsonapi(): ?JsonApi
    {
        return $this->jsonapi;
    }

    /**
     * @inheritDoc
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function meta(): ?Meta
    {
        return $this->meta;
    }

    /**
     * @inheritDoc
     */
    public function links(): ?Links
    {
        return $this->links;
    }

    /**
     * @inheritDoc
     */
    public function included(): ?array
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function withJsonapi(JsonApi $jsonApi): Document
    {
        $copy = clone $this;
        $copy->jsonapi = $jsonApi;
        return $copy;
    }

    /**
     * @inheritDoc
     */
    public function withMeta(Meta $meta): MetaAwareObject
    {
        $copy = clone $this;
        $copy->meta = $meta;
        return $copy;
    }

    /**
     * @inheritDoc
     */
    public function withLinks(Links $links): LinksAwareObject
    {
        $copy = clone $this;
        $copy->links = $links;
        return $copy;
    }
}
