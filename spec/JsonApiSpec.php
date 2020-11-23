<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI;

use Doctrine\Common\Collections\Collection;
use Slick\Http\Message\Uri;
use Slick\JSONAPI\Exception\UnsupportedFeature;
use Slick\JSONAPI\JsonApi;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\Meta;

/**
 * JsonApiSpec specs
 *
 * @package spec\Slick\JSONAPI
 */
class JsonApiSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(JsonApi::JSON_API_11);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(JsonApi::class);
    }

    function it_has_a_version()
    {
        $this->version()->shouldBe(JsonApi::JSON_API_11);
    }

    function it_can_be_created_with_different_version()
    {
        $this->beConstructedWith(JsonApi::JSON_API_10);
        $this->shouldHaveType(JsonApi::class);
    }

    function it_has_a_list_of_extensions()
    {
        $this->extensions()->shouldBeAnInstanceOf(Collection::class);
    }

    function it_can_add_extensions()
    {
        $uri = new Uri('http://www.example.com/ext/atomic');
        $copy = $this->withExtension($uri);
        $copy->shouldBeAnInstanceOf(JsonApi::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->extensions()->first()->shouldBe($uri);
    }

    function it_can_add_a_list_os_extensions()
    {
        $uri1 = new Uri('http://www.example.com/ext/atomic');
        $uri2 = new Uri('http://www.example.com/ext/simple');
        $list = [$uri1, $uri2];
        $copy = $this->withExtensions($list);
        $copy->shouldBeAnInstanceOf(JsonApi::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->extensions()->first()->shouldBe($uri1);
        $copy->extensions()->next()->shouldBe($uri2);
    }

    function it_throws_exception_when_adding_extensions_on_v10()
    {
        $uri = new Uri('http://www.example.com/ext/atomic');
        $this->beConstructedWith(JsonApi::JSON_API_10);
        $this->shouldThrow(UnsupportedFeature::class)
            ->during('withExtension', [$uri]);
    }

    function it_throws_exception_when_using_extensions_on_v11()
    {
        $this->beConstructedWith(JsonApi::JSON_API_10);
        $this->shouldThrow(UnsupportedFeature::class)
            ->during('extensions', []);
    }

    function it_has_a_list_of_profiles()
    {
        $uri = new Uri('https://api.example.com/profiles/timestamps');
        $this->profiles()->shouldBeAnInstanceOf(Collection::class);
        $copy = $this->withProfile($uri);
        $copy->shouldBeAnInstanceOf(JsonApi::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->profiles()->first()->shouldBe($uri);
    }

    function it_can_add_a_list_of_profiles()
    {
        $uri1 = new Uri('http://www.example.com/profiles/atomic');
        $uri2 = new Uri('http://www.example.com/profiles/simple');
        $list = [$uri1, $uri2];

        $copy = $this->withProfiles($list);
        $copy->shouldBeAnInstanceOf(JsonApi::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->profiles()->first()->shouldBe($uri1);
        $copy->profiles()->next()->shouldBe($uri2);
    }

    function it_throws_exception_when_adding_profiles_on_v10()
    {
        $uri = new Uri('http://www.example.com/profiles/atomic');
        $this->beConstructedWith(JsonApi::JSON_API_10);
        $this->shouldThrow(UnsupportedFeature::class)
            ->during('withProfile', [$uri]);
    }

    function it_throws_exception_when_using_profiles_on_v11()
    {
        $this->beConstructedWith(JsonApi::JSON_API_10);
        $this->shouldThrow(UnsupportedFeature::class)
            ->during('profiles', []);
    }

    function it_can_have_a_meta_object()
    {
        $this->meta()->shouldBe(null);
    }

    function it_can_add_a_meta_objet()
    {
        $meta = new Meta(['foo' => 'bar']);
        $copy = $this->withMeta($meta);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->shouldBeAnInstanceOf(JsonApi::class);
        $this->meta()->shouldBe(null);
        $copy->meta()->shouldBe($meta);
    }
}
