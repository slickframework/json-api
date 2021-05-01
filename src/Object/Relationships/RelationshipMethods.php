<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\Relationships;

use phpDocumentor\Reflection\Types\This;
use Slick\JSONAPI\LinksAwareObject;
use Slick\JSONAPI\MetaAwareObject;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Relationship;

/**
 * RelationshipMethods
 *
 * @package Slick\JSONAPI\Object\Relationships
 */
trait RelationshipMethods
{

    /**
     * @var Links|null
     */
    protected $links;

    /**
     * @var Meta|null
     */
    protected $meta;

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
    public function meta(): ?Meta
    {
        return $this->meta;
    }

    /**
     * withLinks
     *
     * @param Links $links
     * @return LinksAwareObject|Relationship
     */
    public function withLinks(Links $links): LinksAwareObject
    {
        $copy = clone $this;
        $copy->links = $links;
        return $copy;
    }

    /**
     * withMeta
     *
     * @param Meta $meta
     * @return MetaAwareObject|Relationship
     */
    public function withMeta(Meta $meta): MetaAwareObject
    {
        $copy = clone $this;
        $copy->meta = $meta;
        return $copy;
    }
}
