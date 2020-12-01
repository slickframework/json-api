<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object;

use Slick\JSONAPI\Object\ErrorObject;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\Links;
use Slick\JSONAPI\Object\Meta;
use Slick\JSONAPI\Object\Resource;

/**
 * ErrorObjectSpec specs
 *
 * @package spec\Slick\JSONAPI\Object
 */
class ErrorObjectSpec extends ObjectBehavior
{

    private $title;
    private $detail;
    private $source;
    private $status;

    function let()
    {
        $this->title = 'Value is too short';
        $this->detail = 'First name must contain at least three characters.';
        $this->source = new ErrorObject\ErrorSource('/data/attributes/firstName');
        $this->status = '422';
        $this->beConstructedWith($this->title, $this->detail, $this->source, $this->status);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ErrorObject::class);
    }

    function its_a_resource()
    {
        $this->shouldBeAnInstanceOf(Resource::class);
    }

    function it_has_a_title()
    {
        $this->title()->shouldBe($this->title);
    }

    function it_has_a_detail()
    {
        $this->detail()->shouldBe($this->detail);
    }

    function it_has_a_source()
    {
        $this->source()->shouldBe($this->source);
    }

    function it_has_a_status()
    {
        $this->status()->shouldBe($this->status);
    }

    function it_has_a_type()
    {
        $this->type()->shouldBe('errors');
    }

    function it_has_a_identifier()
    {
        $this->identifier()->shouldBe(null);
    }

    function it_has_a_resourceIdentifier()
    {
        $this->resourceIdentifier()->type()->shouldBe('errors');
        $this->resourceIdentifier()->identifier()->shouldBe(null);
    }

    function it_can_have_a_list_of_links()
    {
        $links = new Links();
        $this->links()->shouldBe(null);
        $copy = $this->withLinks($links);
        $copy->shouldBeAnInstanceOf(ErrorObject::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->links()->shouldBe($links);
        $this->links()->shouldBe(null);
    }

    function it_can_have_a_code()
    {
        $code = '234';
        $this->code()->shouldBe(null);
        $copy = $this->withCode($code);
        $copy->shouldBeAnInstanceOf(ErrorObject::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->code()->shouldBe($code);
        $this->code()->shouldBe(null);
    }

    function it_can_have_meta_information()
    {
        $meta = new Meta(['foo' => 'bar']);
        $this->meta()->shouldBe(null);
        $copy = $this->withMeta($meta);
        $copy->shouldBeAnInstanceOf(ErrorObject::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->meta()->shouldBe($meta);
        $this->meta()->shouldBe(null);
    }

    function it_may_have_an_identifier()
    {
        $identifier = "some.id";
        $this->identifier()->shouldBe(null);
        $copy = $this->withIdentifier($identifier);
        $copy->shouldBeAnInstanceOf(ErrorObject::class);
        $copy->shouldNotBe($this->getWrappedObject());
        $copy->identifier()->shouldBe($identifier);
        $this->identifier()->shouldBe(null);
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'status' => $this->status,
            'title' => $this->title,
            'detail' => $this->detail,
            'source' => $this->source
        ]);
    }
}