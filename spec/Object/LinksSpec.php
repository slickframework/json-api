<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object;

use IteratorAggregate;
use Slick\JSONAPI\Object\Link\LinkObject;
use Slick\JSONAPI\Object\Links;
use PhpSpec\ObjectBehavior;

/**
 * LinksSpec specs
 *
 * @package spec\Slick\JSONAPI\Object
 */
class LinksSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Links::class);
    }

    function it_can_add_link()
    {
        $href = '/user/1';
        $this->add(Links::LINK_SELF, $href)->shouldBe($this->getWrappedObject());
        $this->get(Links::LINK_SELF)->href()->shouldBe($href);
    }

    function it_an_iterator()
    {
        $this->shouldBeAnInstanceOf(IteratorAggregate::class);
    }

    function it_can_add_a_constructed_link()
    {
        $link = new LinkObject(Links::LINK_RELATED, '/post/4/author', 'Author relation');
        $this->add($link)->shouldBe($this->getWrappedObject());
        $this->get(Links::LINK_RELATED)->shouldBe($link);
    }

    function it_can_remove_a_link()
    {
        $href = '/user/1';
        $this->add(Links::LINK_SELF, $href);
        $this->remove(Links::LINK_SELF)->shouldBe($this->getWrappedObject());
        $this->get(Links::LINK_SELF)->shouldBe(null);
    }

    function it_will_change_href_when_created_with_prefix()
    {
        $prefix = 'https://api.sd.co/';
        $this->beConstructedWith($prefix);
        $path = '/user/1';
        $this->add(Links::LINK_SELF, $path);
        $this->get(Links::LINK_SELF)->href()->shouldBe(rtrim($prefix, ' /').$path);
    }

    function it_can_also_change_link_objects_when_prefix_is_given()
    {
        $prefix = 'https://api.sd.co/';
        $this->beConstructedWith($prefix);
        $path = '/user/32';
        $linkObject = new LinkObject(Links::LINK_SELF, $path);
        $this->add($linkObject);
        $this->get(Links::LINK_SELF)->href()->shouldBe(rtrim($prefix, ' /').$path);
    }

    function it_dont_change_full_valid_uri_when_prefix_is_given()
    {
        $prefix = 'https://api.sd.co/';
        $this->beConstructedWith($prefix);

        $hrefSelf = 'https://some.api.pt/user/1';

        $this->add(Links::LINK_SELF, $hrefSelf);
        $this->get(Links::LINK_SELF)->href()->shouldBe($hrefSelf);

        $hrefRel = 'https://some.api.pt/user/1/address';
        $linkObject = new LinkObject(Links::LINK_RELATED, $hrefRel);

        $this->add($linkObject);
        $this->get(Links::LINK_RELATED)->href()->shouldBe($hrefRel);
    }

    function it_can_be_converted_to_json()
    {
        $hrefSelf = 'https://some.api.pt/user/1';
        $hrefRel = 'https://some.api.pt/user/1/address';
        $linkObject = new LinkObject(Links::LINK_RELATED, $hrefRel);

        $this
            ->add(Links::LINK_SELF, $hrefSelf)
            ->add($linkObject);

        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            Links::LINK_SELF => $this->get(Links::LINK_SELF),
            Links::LINK_RELATED => $linkObject
        ]);
    }
}