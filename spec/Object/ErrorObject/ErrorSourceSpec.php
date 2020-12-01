<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\ErrorObject;

use Slick\JSONAPI\Exception\InvalidObjectCreation;
use Slick\JSONAPI\Object\ErrorObject\ErrorSource;
use PhpSpec\ObjectBehavior;
use Slick\JSONAPI\Object\Resource;

/**
 * ErrorSourceSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\ErrorObject
 */
class ErrorSourceSpec extends ObjectBehavior
{

    private $pointer;
    private $parameter;

    function let()
    {
        $this->pointer = '/data/attributes/name';
        $this->parameter = 'filter[pattern]';
        $this->beConstructedWith($this->pointer, $this->parameter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ErrorSource::class);
    }

    function it_has_a_pointer()
    {
        $this->pointer()->shouldBe($this->pointer);
    }

    function it_has_a_parameter()
    {
        $this->parameter()->shouldBe($this->parameter);
    }


    function it_cannot_be_created_without_any_arguments()
    {
        $this->beConstructedWith();
        $this->shouldThrow(InvalidObjectCreation::class)
            ->duringInstantiation();
    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'pointer' => $this->pointer,
            'parameter' => $this->parameter
        ]);
    }
}