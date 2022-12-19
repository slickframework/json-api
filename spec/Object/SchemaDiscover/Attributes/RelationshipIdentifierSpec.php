<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes;

use Slick\JSONAPI\Object\SchemaDiscover\Attributes\RelationshipIdentifier;
use PhpSpec\ObjectBehavior;

/**
 * RelationshipIdentifierSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes
 */
class RelationshipIdentifierSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith('owner');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RelationshipIdentifier::class);
    }
}