<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object\Link;

use JsonSerializable;
use Slick\JSONAPI\Exception\FailedValidation;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Validator\JsonApiValidator;

/**
 * LinkObject
 *
 * @package Slick\JSONAPI\Object\Link
 */
final class LinkObject implements JsonSerializable
{
    /**
     * @var string|null
     */
    private ?string $type = null;

    /**
     * @var string|array<string>|null
     */
    private string|array|null $hreflang = null;

    /**
     * @var Meta|null
     */
    private ?Meta $meta = null;

    /**
     * Creates a LinkObject
     *
     * @param string $href
     * @param string|null $rel
     * @param string|null $title
     * @param string|null $describedBy
     */
    public function __construct(
        private string $href,
        private ?string $rel = null,
        private ?string $title = null,
        private ?string $describedBy = null
    ) {
        if ($rel && !JsonApiValidator::instance()->isValid($rel, JsonApiValidator::VALIDATE_LINK_REL)) {
            throw new FailedValidation(
                "'$rel' is not a valid or known RFC8288 link relation type."
            );
        }
    }

    /**
     * rel
     *
     * @return null|string
     */
    public function rel(): ?string
    {
        return $this->rel;
    }

    /**
     * href
     *
     * @return string
     */
    public function href(): string
    {
        return $this->href;
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
     * describedBy
     *
     * @return string|null
     */
    public function describedBy(): ?string
    {
        return $this->describedBy;
    }

    /**
     * Returns a link with the given href
     *
     * This method will ALWAYS return a new copy (clone) of the Link object
     * maintaining object immutability.
     *
     * @param string $href
     * @return LinkObject
     */
    public function withHref(string $href): LinkObject
    {
        $copy = clone $this;
        $copy->href = $href;
        return $copy;
    }

    /**
     * Returns a link with a given title
     *
     * This method will ALWAYS return a new copy (clone) of the Link object
     * maintaining object immutability.
     *
     * @param string $title
     * @return LinkObject
     */
    public function withTitle(string $title): LinkObject
    {
        $copy = clone $this;
        $copy->title = $title;
        return $copy;
    }

    /**
     * Returns a link with a new described by href
     *
     * This method will ALWAYS return a new copy (clone) of the Link object
     * maintaining object immutability.
     *
     * @param string $describedBy
     * @return LinkObject
     */
    public function withDescribedBy(string $describedBy): LinkObject
    {
        $copy = clone $this;
        $copy->describedBy = $describedBy;
        return $copy;
    }

    /**
     * Returns a link with a new media type
     *
     * This method will ALWAYS return a new copy (clone) of the Link object
     * maintaining object immutability.
     *
     * @param string $type
     * @return LinkObject
     */
    public function withType(string $type): LinkObject
    {
        $copy = clone $this;
        $copy->type = $type;
        return $copy;
    }

    /**
     * A string indicating the media type of the link’s target.
     *
     * @return string|null
     */
    public function type(): ?string
    {
        return $this->type;
    }

    /**
     * A string or an array of strings indicating the language(s) of the link’s target.
     *
     * @return string|array<string>|null
     */
    public function hreflang(): array|string|null
    {
        return $this->hreflang;
    }

    /**
     * Returns a link with a new hreflang
     *
     * This method will ALWAYS return a new copy (clone) of the Link object
     * maintaining object immutability.
     *
     * @param $hreflang
     * @return LinkObject
     * @throws FailedValidation for invalid or unknown language types
     */
    public function withHreflang($hreflang): LinkObject
    {
        if (!is_string($hreflang) && !is_array($hreflang)) {
            throw new FailedValidation(
                "Link's 'hreflang' should be a string or and array of strings containing ".
                "available link target languages."
            );
        }

        $validate = is_string($hreflang) ? [$hreflang] : $hreflang;
        foreach ($validate as $lang) {
            if (!JsonApiValidator::instance()->isValid($lang, JsonApiValidator::VALIDATE_HREF_LANG)) {
                throw new FailedValidation("'$lang' is not a valid RFC5646 language tag.");
            }
        }

        $copy = clone $this;
        $copy->hreflang = $hreflang;
        return $copy;
    }

    /**
     * A META object containing non-standard meta-information about the link
     *
     * @return Meta|null
     */
    public function meta(): ?Meta
    {
        return $this->meta;
    }

    /**
     * Returns a link with a new META object
     *
     * This method will ALWAYS return a new copy (clone) of the Link object
     * maintaining object immutability.
     *
     * @param Meta $meta
     * @return LinkObject
     */
    public function withMeta(Meta $meta): LinkObject
    {
        $copy = clone $this;
        $copy->meta = $meta;
        return $copy;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string|array
    {
        $data = ['href' => $this->href];
        $properties = ['title', 'describedBy', 'type', 'hreflang', 'meta'];
        foreach ($properties as $property) {
            if ($this->$property) {
                $data[$property] = $this->$property;
            }
        }

        if (count($data) > 1 && $this->rel) {
            $data = array_merge(['rel' => $this->rel], $data);
        }

        return count($data) === 1 ? $this->href : $data;
    }
}
