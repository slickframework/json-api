<?php

/**
 * This file is part of json-api
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Slick\JSONAPI\Document\Decoder\Fixtures;

use Slick\JSONAPI\Object\SchemaDiscover\Attributes\ResourceAttribute;

/**
 * User
 *
 * @package Document\Decoder\Fixtures
 */
final class User extends Person
{

    public function __construct(
        string $name,
        #[ResourceAttribute]
        private string $email
    ) {
        parent::__construct($name);
    }

    public function email(): string
    {
        return $this->email;
    }
}
