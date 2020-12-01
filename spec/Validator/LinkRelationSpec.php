<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Validator;

use Slick\JSONAPI\Validator;
use Slick\JSONAPI\Validator\LinkRelation;
use PhpSpec\ObjectBehavior;

/**
 * LinkRelationSpec specs
 *
 * @package spec\Slick\JSONAPI\Validator
 */
class LinkRelationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LinkRelation::class);
    }

    function its_a_validator()
    {
        $this->shouldBeAnInstanceOf(Validator::class);
    }

    function it_can_validate_a_link_relation()
    {
        $this->isValid('related')->shouldBe(true);
    }

    function it_will_not_validate_unknown_relation()
    {
        $this->isValid('some-unknown-rel')->shouldBe(false);
    }
}