<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object;

use Slick\JSONAPI\Object\Meta;
use PhpSpec\ObjectBehavior;

/**
 * MetaSpec specs
 *
 * @package spec\Slick\JSONAPI\Object
 */
class MetaSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['foo' => 'bar']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Meta::class);
    }

    function it_has_a_getter()
    {
        $this->get('foo')->shouldBe('bar');
    }

    function it_can_et_new_entries()
    {
        $copy = $this->with('bar', 'baz');
        $copy->shouldBeAnInstanceOf(Meta::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $this->get('bar')->shouldBe(null);
        $this->get('foo')->shouldBe('bar');

        $copy->get('foo')->shouldBe('bar');
        $copy->get('bar')->shouldBe('baz');
    }

    function it_can_add_multiple_entries()
    {
        $array = ['one', 'two'];
        $copy = $this->with(['bar' => 'baz', 'test' => $array]);
        $this->get('test')->shouldBe(null);
        $copy->shouldBeAnInstanceOf(Meta::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->get('test')->shouldBe($array);
    }

    function it_can_be_converted_to_json()
    {
        $this->beConstructedWith([
            'copyright' => "Copyright 2015 Example Corp.",
            'authors' => [
                "Yehuda Katz",
                "Steve Klabnik",
                "Dan Gebhardt",
                "Tyler Kellen"
            ]
        ]);
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'copyright' => "Copyright 2015 Example Corp.",
            'authors' => [
                "Yehuda Katz",
                "Steve Klabnik",
                "Dan Gebhardt",
                "Tyler Kellen"
            ]
        ]);
    }
}