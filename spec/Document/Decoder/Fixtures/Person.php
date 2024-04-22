<?php

/**
 * This file is part of json-api
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
 * Person
 *
 * @package Document\Decoder\Fixtures
 */
#[AsResourceObject(type: "users")]
abstract class Person
{
    #[ResourceIdentifier]
    protected string $personId;

    public function __construct(
        #[ResourceAttribute]
        protected string $name
    ) {
        $this->personId = uniqid("u-");
    }

    public function personId(): string
    {
        return $this->personId;
    }

    public function name(): string
    {
        return $this->name;
    }
}
