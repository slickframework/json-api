<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Validator;

use Slick\JSONAPI\Exception\FailedValidation;
use Slick\JSONAPI\Object\ErrorObject;
use Slick\JSONAPI\Object\Resource;
use Slick\JSONAPI\Validator\SchemaValidator;
use PhpSpec\ObjectBehavior;

/**
 * SchemaValidatorSpec specs
 *
 * @package spec\Slick\JSONAPI\Validator
 */
class SchemaValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SchemaValidator::class);
    }

    function it_can_add_errors()
    {
        $error = $this->add('Some error', 'An error has occurred');
        $error->shouldBe($this->getWrappedObject());
        $this->exception()->document()->errors()->first()->shouldBeAnInstanceOf(ErrorObject::class);
    }

    function it_can_have_a_validation_exception()
    {
        $this->exception()->shouldBe(null);
        $this->add('Some error', 'An error has occurred')
            ->shouldBe($this->getWrappedObject());
        $this->exception()->shouldBeAnInstanceOf(FailedValidation::class);
    }

    function it_validates_if_no_errors_are_stored(Resource $resource)
    {
        $this->isValid($resource)->shouldBe(true);
    }

    function it_fails_validation_when_there_is_at_least_one_error(Resource $resource)
    {
        $this->add('Some error', 'An error has occurred')
            ->shouldBe($this->getWrappedObject());
        $this->isValid($resource)->shouldBe(false);
    }
}