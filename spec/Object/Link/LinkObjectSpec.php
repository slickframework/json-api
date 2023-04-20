<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\Link;

use Slick\JSONAPI\Exception\FailedValidation;
use Slick\JSONAPI\Object\Link\LinkObject;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\Meta;

/**
 * LinkObjectSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\Link
 */
class LinkObjectSpec extends ObjectBehavior
{

    private $rel;
    private $href;
    private $title;
    private $describedBy;

    function let()
    {
        $this->rel = 'self';
        $this->href = '/users/2';
        $this->title = 'title';
        $this->describedBy = 'describedBy';
        $this->beConstructedWith($this->href, $this->rel, $this->title, $this->describedBy);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LinkObject::class);
    }

    function it_has_a_link_relation()
    {
        $this->rel()->shouldBe($this->rel);
    }

    function it_throws_exception_when_link_relation_is_invalid()
    {
        $this->beConstructedWith('Hello-world', '/test');
        $this->shouldThrow(FailedValidation::class)
            ->duringInstantiation();
    }

    function it_has_an_href()
    {
        $this->href()->shouldBe($this->href);
    }

    function it_can_be_created_only_with_rel_and_href()
    {
        $this->beConstructedWith($this->rel, $this->href);
        $this->shouldBeAnInstanceOf(LinkObject::class);
    }

    function it_has_a_title()
    {
        $this->title()->shouldBe($this->title);
    }

    function it_has_a_described_by()
    {
        $this->describedBy()->shouldBe($this->describedBy);
    }

    function it_can_change_href()
    {
        $href = '/some/new/href';
        $copy = $this->withHref($href);
        $this->href()->shouldBe($this->href);
        $copy->shouldBeAnInstanceOf(LinkObject::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->href()->shouldBe($href);
    }

    function it_can_change_its_title()
    {
        $title = 'some title';
        $copy = $this->withTitle($title);
        $this->title()->shouldBe($this->title);
        $copy->shouldBeAnInstanceOf(LinkObject::class);
        $copy->title()->shouldBe($title);
    }

    function it_can_change_described_by()
    {
        $describedBy = 'http://some.com/description/api.json';
        $copy = $this->withDescribedBy($describedBy);
        $this->describedBy()->shouldBe($this->describedBy);
        $copy->shouldBeAnInstanceOf(LinkObject::class);
        $copy->describedBy()->shouldBe($describedBy);
    }

    function it_can_change_its_type()
    {
        $type = 'image/png';
        $copy = $this->withType($type);
        $this->type()->shouldBe(null);
        $copy->shouldBeAnInstanceOf(LinkObject::class);
        $copy->type()->shouldBe($type);
    }

    function it_may_have_an_hreflang()
    {
        $this->hreflang()->shouldBe(null);
        $hreflang = 'pt-PT';
        $copy = $this->withHreflang($hreflang);
        $this->hreflang()->shouldBe(null);
        $copy->hreflang()->shouldBe($hreflang);
    }

    function its_hreflang_can_be_a_list_of_languages()
    {
        $hreflang = ['en-US', 'pt-PT', 'fr'];
        $copy = $this->withHreflang($hreflang);
        $copy->hreflang()->shouldBe($hreflang);
    }

    function it_throws_exception_on_invalid_language()
    {
        $hreflang = ['en-US', 'pt-PT', 'feels-good'];
        $this->shouldThrow(FailedValidation::class)
            ->during('withHreflang', [$hreflang]);
    }

    function it_may_have_a_meta_object()
    {
        $meta = new Meta(['foo' => 'bar']);
        $this->meta()->shouldBe(null);
        $copy = $this->withMeta($meta);
        $copy->shouldBeAnInstanceOf(LinkObject::class);
        $this->meta()->shouldBe(null);
        $copy->meta()->shouldBe($meta);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'rel' => $this->rel,
            'href' => $this->href,
            'title' => $this->title,
            'describedBy' => $this->describedBy
        ]);
    }

    function it_only_return_href_on_json_fot_href_only_links()
    {
        $this->beConstructedWith($this->href, $this->rel);
        $this->jsonSerialize()->shouldBe($this->href);
    }
}
