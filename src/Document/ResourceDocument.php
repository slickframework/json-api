<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document;

use Slick\JSONAPI\Document;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Resource;
use Slick\JSONAPI\Object\ResourceObject;

/**
 * ResourceDocument
 *
 * @package Slick\JSONAPI\Document
 */
class ResourceDocument implements Document
{

    use DocumentMethods;

    /**
     * @var ResourceObject
     */
    protected $data;

    /**
     * Creates a ResourceDocument
     *
     * @param Resource $data
     * @param Links|null $links
     * @param Meta|null $meta
     */
    public function __construct(Resource $data, ?Links $links = null, ?Meta $meta = null)
    {
        $this->data = $data;
        $this->links = $links;
        $this->meta = $meta;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $data = $this->jsonapi ? ['jsonapi' => $this->jsonapi] : [];
        $data['data'] = $this->data;
        $data = $this->links ? array_merge($data, ['links' => $this->links]) : $data;
        return $this->meta ? array_merge($data, ['meta' => $this->meta]) : $data;
    }
}
