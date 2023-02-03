<?php

/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Slick\JSONAPI\Document\Decoder\Fixtures;

use Slick\JSONAPI\Object\SchemaDiscover\Attributes\AsResourceObject;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceAttribute;
use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceIdentifier;

/**
 * Post
 *
 * @package spec\Slick\JSONAPI\Document\Decoder\Fixtures
 */
#[AsResourceObject(type: "posts", meta: "meta", links: "links")]
final class Post
{

    public function __construct(
        #[ResourceIdentifier]
        private string $id,
        #[ResourceAttribute]
        private string $title,
        #[ResourceAttribute]
        private ?string $body = null
    ) {
    }

    /**
     * Post's id
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Post's title
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Post's body
     * @return string|null
     */
    public function body(): ?string
    {
        return $this->body;
    }

    public function meta(): array
    {
        return ["foo" => "bar"];
    }

    public function links(): array
    {
        return [
            'status' => "/some/link"
        ];
    }
}
