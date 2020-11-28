<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\JSONAPI\Object;

/**
 * ResourceIdentifier
 *
 * @package Slick\JSONAPI\Object
 */
final class ResourceIdentifier implements Resource
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string|null
     */
    private $identifier;

    /**
     * @var bool
     */
    private $isLocal = false;

    /**
     * Creates a ResourceIdentifier
     *
     * @param string $type
     * @param string|null $identifier
     */
    public function __construct(string $type, ?string $identifier = null)
    {
        $this->type = $type;
        $this->identifier = $identifier;
    }

    /**
     * Creates a local resource identifier
     *
     * @param string $type
     * @param string $identifier
     * @return ResourceIdentifier
     */
    public static function localIdentifier(string $type, string $identifier): ResourceIdentifier
    {
        $resourceIdentifier = new ResourceIdentifier($type, $identifier);
        $resourceIdentifier->isLocal = true;
        return $resourceIdentifier;
    }

    /**
     * type
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * identifier
     *
     * @return string|null
     */
    public function identifier(): ?string
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $key = $this->isLocal ? 'lid' : 'id';
        return [
            'type' => $this->type,
            $key => $this->identifier
        ];
    }

    /**
     * @inheritDoc
     */
    public function resourceIdentifier(): ResourceIdentifier
    {
        return $this;
    }
}
