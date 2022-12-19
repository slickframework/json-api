<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Slick\JSONAPI\Object\SchemaDiscover\Attributes\Relationship;
use PhpSpec\ObjectBehavior;

/**
 * RelationshipSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
class RelationshipSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Relationship::class);
    }
}