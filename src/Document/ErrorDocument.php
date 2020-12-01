<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Document;

use BadMethodCallException;
use Slick\JSONAPI\Document;
use Slick\JSONAPI\JsonApi;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\ResourceCollection;

/**
 * ErrorDocument
 *
 * @package Slick\JSONAPI\Document
 */
final class ErrorDocument implements Document
{

    use DocumentMethods;

    /**
     * @var ResourceCollection
     */
    private $errors;


    /**
     * Creates a ErrorDocument
     *
     * @param ResourceCollection $errors
     * @param JsonApi|null $jsonapi
     */
    public function __construct(ResourceCollection $errors, ?JsonApi $jsonapi = null)
    {
        $this->errors = $errors;
        $this->jsonapi = $jsonapi;
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
    public function meta(): ?Meta
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function withMeta(Meta $meta): Document
    {
        throw new BadMethodCallException(
            "Error document does not have a meta member."
        );
    }

    /**
     * @inheritDoc
     */
    public function links(): ?Links
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function withLinks(Links $links): Document
    {
        throw new BadMethodCallException(
            "Error document does not have a links member."
        );
    }

    /**
     * errors
     *
     * @return ResourceCollection
     */
    public function errors(): ResourceCollection
    {
        return $this->errors;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $data = $this->jsonapi ? ['jsonapi' => $this->jsonapi] : [];
        $data['errors'] = $this->errors;
        return $data;
    }
}
