<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use Psr\Http\Message\UriInterface;
use Slick\JSONAPI\Exception\UnsupportedFeature;
use Slick\JSONAPI\Exception\UnsupportedJsonApiVersion;
use Slick\JSONAPI\Object\Meta;

/**
 * JsonApi
 *
 * @package Slick\JSONAPI
 *
 * @see https://jsonapi.org/format/#document-jsonapi-object
 */
final class JsonApi implements MetaAwareObject, JsonSerializable
{
    public const JSON_API_10 = '1.0';
    public const JSON_API_11 = '1.1';

    private const ALLOWED_VERSIONS = [self::JSON_API_10, self::JSON_API_11];

    private const EXTENSIONS_SUPPORT_ERROR =
        "Extensions are only supported on JSON:API version 1.1. JsonApi::__construct() was called with version 1.0.";
    private const PROFILES_SUPPORT_ERROR =
        "Profiles are only supported on JSON:API version 1.1. JsonApi::__construct() was called with version 1.0.";
    private const META_SUPPORT_ERROR =
        "Meta objects are only supported on JSON:API version 1.1. JsonApi::__construct() was called with version 1.0.";

    /** @var string */
    private $version;

    /** @var Collection|UriInterface[] */
    private $extensions;

    /** @var Collection|UriInterface[] */
    private $profiles;

    /** @var Meta|null */
    private $meta;

    /**
     * Creates a JsonApi object
     *
     * @param string $version
     * @param array $extensions
     * @param array $profiles
     * @param Meta|null $meta
     * @see https://jsonapi.org/format/#document-jsonapi-object
     */
    public function __construct(
        string $version = self::JSON_API_10,
        array $extensions = [],
        array $profiles = [],
        ?Meta $meta = null
    ) {
        if (!in_array($version, self::ALLOWED_VERSIONS)) {
            throw new UnsupportedJsonApiVersion("Unknown or unsupported JSON:API version.");
        }

        $this->version = $version;
        $this->extensions = new ArrayCollection($extensions);
        $this->profiles = new ArrayCollection($profiles);
        $this->meta = $meta;
    }

    /**
     * Version member indicating the highest JSON API version supported
     *
     * @return string
     */
    public function version(): string
    {
        return $this->version;
    }

    /**
     * An array of URIs for all applied extensions.
     *
     * @return Collection|array<UriInterface>
     * @throws UnsupportedFeature when trying to access extensions on a 1.0 version object
     */
    public function extensions(): Collection
    {
        $this->checkExtensionUsage();
        return $this->extensions;
    }

    /**
     * Adds an extension to the list of extensions
     *
     * This method will ALWAYS return a new copy (clone) of the JsonApi object
     * maintaining object immutability.
     *
     * @param UriInterface $uri
     * @return JsonApi
     * @throws UnsupportedFeature when trying to add an extension to a 1.0 version object
     */
    public function withExtension(UriInterface $uri): JsonApi
    {
        $this->checkExtensionUsage();

        $copy = clone $this;
        $copy->extensions->add($uri);
        return $copy;
    }

    /**
     * Adds a list of extension to the extensions's collection
     *
     * @param array<UriInterface> $extensions
     * @return JsonApi
     * @throws UnsupportedFeature when trying to add an extension to a 1.0 version object
     */
    public function withExtensions(array $extensions): JsonApi
    {
        $copy = $this;
        foreach ($extensions as $extension) {
            $copy = $copy->withExtension($extension);
        }
        return $copy;
    }

    /**
     * An array of URIs for all applied profiles.
     *
     * @return Collection|UriInterface[]
     * @throws UnsupportedFeature when trying to access profiles on a 1.0 version object
     */
    public function profiles(): Collection
    {
        $this->checkProfilesUsage();

        return $this->profiles;
    }

    /**
     * Adds a profile to the list of profiles
     *
     * This method will ALWAYS return a new copy (clone) of the JsonApi object
     * maintaining object immutability.
     *
     * @param UriInterface $uri
     * @return JsonApi
     * @throws UnsupportedFeature when trying to add a profile to a 1.0 version object
     */
    public function withProfile(UriInterface $uri): JsonApi
    {
        $this->checkProfilesUsage();

        $copy = clone $this;
        $copy->profiles->add($uri);
        return $copy;
    }

    /**
     * Adds a list of profiles to the profile's collection
     *
     * @param array $profiles
     * @return JsonApi
     * @throws UnsupportedFeature when trying to add a profile to a 1.0 version object
     */
    public function withProfiles(array $profiles): JsonApi
    {
        $copy = clone $this;
        foreach ($profiles as $profile) {
            $copy = $copy->withProfile($profile);
        }
        return $copy;
    }

    /**
     * A meta object that contains non-standard meta-information.ta
     *
     * @return Meta|null
     * @throws UnsupportedFeature when trying to access meta object on a 1.0 version object
     */
    public function meta(): ?Meta
    {
        $this->checkMetaUsage();

        return $this->meta;
    }

    /**
     * Adds a meta information object to the JsonApi object
     *
     * @param Meta $meta
     * @return JsonApi
     * @throws UnsupportedFeature when trying to add a meta object to a 1.0 version object
     */
    public function withMeta(Meta $meta): MetaAwareObject
    {
        $this->checkMetaUsage();

        $copy = clone $this;
        $copy->meta = $meta;
        return $copy;
    }

    /**
     * Checks version 1.0 extension usage
     * @throws UnsupportedFeature
     */
    private function checkExtensionUsage(): void
    {
        if ($this->version === self::JSON_API_10) {
            throw new UnsupportedFeature(self::EXTENSIONS_SUPPORT_ERROR);
        }
    }

    /**
     * Checks version 1.0 profiles usage
     * @throws UnsupportedFeature
     */
    private function checkProfilesUsage(): void
    {
        if ($this->version === self::JSON_API_10) {
            throw new UnsupportedFeature(self::PROFILES_SUPPORT_ERROR);
        }
    }

    /**
     * Checks version 1.0 profiles usage
     * @throws UnsupportedFeature
     */
    private function checkMetaUsage(): void
    {
        if ($this->version === self::JSON_API_10) {
            throw new UnsupportedFeature(self::META_SUPPORT_ERROR);
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        $data = ['version' => $this->version];
        $this->parseCollection('ext', $this->extensions, $data);
        $this->parseCollection('profile', $this->profiles, $data);

        return $this->meta ? array_merge($data, ['meta' => $this->meta]) : $data;
    }

    /**
     * Parse a give collection and fetch it to the data holder
     *
     * @param string $member
     * @param Collection $collection
     * @param array $data
     */
    private function parseCollection(string $member, Collection $collection, array &$data): void
    {
        if ($collection->isEmpty()) {
            return;
        }

        foreach ($collection as $item) {
            $data[$member][] = (string) $item;
        }
    }
}
