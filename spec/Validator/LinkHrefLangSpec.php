<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Validator;

use Slick\JSONAPI\Validator;
use Slick\JSONAPI\Validator\LinkHrefLang;
use PhpSpec\ObjectBehavior;

/**
 * LinkHrefLangSpec specs
 *
 * @package spec\Slick\JSONAPI\Validator
 */
class LinkHrefLangSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LinkHrefLang::class);
    }

    function its_a_validator()
    {
        $this->shouldBeAnInstanceOf(Validator::class);
    }

    function it_validates_RFC5646_language()
    {
        $this->isValid('pt')->shouldBe(true);
    }

    function it_fails_non_RFC5646_language()
    {
        $this->isValid('pt-nl-lk')->shouldBe(false);
    }
}