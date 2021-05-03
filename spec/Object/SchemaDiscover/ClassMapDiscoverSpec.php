<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\SchemaDiscover;

require 'TestSchema.php';

use Object\SchemaDiscover\TestSchema;
use Slick\JSONAPI\Exception\DocumentEncoderFailure;
use Slick\JSONAPI\Object\SchemaDiscover;
use Slick\JSONAPI\Object\SchemaDiscover\ClassMapDiscover;
use PhpSpec\ObjectBehavior;

/**
 * ClassMapDiscoverSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover
 */
class ClassMapDiscoverSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([
            'test' => TestSchema::class,
            \stdClass::class => TestSchema::class
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ClassMapDiscover::class);
    }

    function its_a_schema_discover()
    {
        $this->shouldBeAnInstanceOf(SchemaDiscover::class);
    }

    function it_can_create_schema_from_string_key()
    {
        $this->discover('test')->shouldBeAnInstanceOf(TestSchema::class);
    }

    function it_can_create_schema_from_class_name()
    {
        $this->discover((object)[])->shouldBeAnInstanceOf(TestSchema::class);
    }

    function it_can_change_the_map()
    {
        $this->map(\stdClass::class, ['type' => 'tests'])->shouldBeAnInstanceOf(ClassMapDiscover::class);
    }

    function it_can_create_schema_from_array()
    {
        $this->map('test', ['type' => 'tests']);
        $this->discover('test')->shouldBeAnInstanceOf(SchemaDiscover\ArraySchema::class);
    }

    function it_can_create_from_callable_returning_a_schema()
    {
        $schema = new TestSchema();
        $this->map(
            'test',
            function () use ($schema) {
                return $schema;
            }
        );
        $this->discover('test')->shouldBe($schema);
    }

    function it_can_create_from_callable_returning_an_array()
    {
        $this->map(
            'test',
            function ($object) {
                return [
                    'type' => 'tests',
                    'attributes' => [
                        'result' => $object
                    ]
                ];
            }
        );
        $this->discover('test')->attributes('test')['result']->shouldBe('test');
    }

    function it_trows_exception_on_missing_map()
    {
        $this->shouldThrow(DocumentEncoderFailure::class)
            ->during('discover', ['other-test']);
    }

    function it_can_check_if_a_document_is_convertible()
    {
        $this->map(
            'test',
            function ($object) {
                return [
                    'type' => 'tests',
                    'attributes' => [
                        'result' => $object
                    ]
                ];
            }
        );
        $this->isConvertible('test')->shouldBe(true);
        $this->isConvertible('other')->shouldBe(false);
    }
}