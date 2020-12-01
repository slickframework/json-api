<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object;

use Slick\JSONAPI\Object\ErrorObject\ErrorSource;

/**
 * ErrorObject
 *
 * @package Slick\JSONAPI\Object
 */
final class ErrorObject implements Resource
{
    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $detail;

    /**
     * @var ErrorSource|null
     */
    private $source;

    /**
     * @var string|null
     */
    private $status;

    /**
     * @var string|null
     */
    private $identifier;

    /**
     * @var Links|null
     */
    private $links;

    /**
     * @var string|null
     */
    private $code;

    /**
     * @var Meta|null
     */
    private $meta;

    /**
     * Creates a ErrorObject
     *
     * @param string|null $title
     * @param string|null $detail
     * @param ErrorSource|null $source
     * @param string|null $status
     */
    public function __construct(
        ?string $title = null,
        ?string $detail = null,
        ?ErrorSource $source = null,
        ?string $status = null
    ) {
        $this->title = $title;
        $this->detail = $detail;
        $this->source = $source;
        $this->status = $status;
    }

    /**
     * title
     *
     * @return string|null
     */
    public function title(): ?string
    {
        return $this->title;
    }

    /**
     * detail
     *
     * @return string|null
     */
    public function detail(): ?string
    {
        return $this->detail;
    }

    /**
     * source
     *
     * @return ErrorSource|null
     */
    public function source(): ?ErrorSource
    {
        return $this->source;
    }

    /**
     * status
     *
     * @return string|null
     */
    public function status(): ?string
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        return 'errors';
    }

    /**
     * @inheritDoc
     */
    public function identifier(): ?string
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public function resourceIdentifier(): ResourceIdentifier
    {
        return new ResourceIdentifier($this->type(), $this->identifier());
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $data = $this->identifier ? ['id' => $this->identifier] : [];
        $properties = ['links', 'status', 'code', 'title', 'detail', 'source', 'meta'];
        foreach ($properties as $property) {
            if (property_exists($this, $property) && $this->$property !== null) {
                $data[$property] = $this->$property;
            }
        }
        return $data;
    }

    /**
     * A links object containing the following members:
     *
     * - about: a link that leads to further details about this particular
     *          occurrence of the problem.
     * - type: a link that identifies the type of error that this particular
     *         error is an instance of.
     *
     * @return Links|null
     */
    public function links(): ?Links
    {
        return $this->links;
    }

    /**
     * Returns a new error object with provided links
     *
     * This method will ALWAYS return a new copy (clone) of the Error object
     * maintaining object immutability.
     *
     * @param Links $links
     * @return ErrorObject
     */
    public function withLinks(Links $links): ErrorObject
    {
        $copy = clone $this;
        $copy->links = $links;
        return $copy;
    }

    /**
     * An application-specific error code, expressed as a string value.
     *
     * @return string|null
     */
    public function code(): ?string
    {
        return $this->code;
    }

    /**
     * Returns a new error object with provided code
     *
     * This method will ALWAYS return a new copy (clone) of the Error object
     * maintaining object immutability.
     *
     * @param string $code
     * @return ErrorObject
     */
    public function withCode(string $code): ErrorObject
    {
        $copy = clone $this;
        $copy->code = $code;
        return $copy;
    }

    /**
     * A meta object containing non-standard meta-information about the error.
     *
     * @return Meta|null
     */
    public function meta(): ?Meta
    {
        return $this->meta;
    }

    /**
     * Returns a new error object with provided meta information
     *
     * This method will ALWAYS return a new copy (clone) of the Error object
     * maintaining object immutability.
     *
     * @param Meta $meta
     * @return ErrorObject
     */
    public function withMeta(Meta $meta): ErrorObject
    {
        $copy = clone $this;
        $copy->meta = $meta;
        return $copy;
    }

    /**
     * Returns a new error object with provided error identifier
     *
     * This method will ALWAYS return a new copy (clone) of the Error object
     * maintaining object immutability.
     *
     * @param string $identifier
     * @return ErrorObject
     */
    public function withIdentifier(string $identifier): ErrorObject
    {
        $copy = clone $this;
        $copy->identifier = $identifier;
        return $copy;
    }
}
