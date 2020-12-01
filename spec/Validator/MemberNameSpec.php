<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Validator;

use Slick\JSONAPI\Validator;
use Slick\JSONAPI\Validator\MemberName;
use PhpSpec\ObjectBehavior;

/**
 * MemberNameSpec specs
 *
 * @package spec\Slick\JSONAPI\Validator
 */
class MemberNameSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(MemberName::class);
    }

    function its_a_validator()
    {
        $this->shouldBeAnInstanceOf(Validator::class);
    }

    function it_validates_allowed_characters()
    {
        $this->isValid('Some')->shouldBe(true);
    }

    function it_dont_validates_forbidden_characters()
    {
        $this->isValid('s\'ome:i;ç~_invalid%$#!_cars[]()')->shouldBe(false);
    }
}