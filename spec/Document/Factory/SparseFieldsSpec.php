<?php
/**
 * This file is part of JsonApi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Document\Factory;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ServerRequestInterface;
use Slick\JSONAPI\Document\Factory\SparseFields;

class SparseFieldsSpec extends ObjectBehavior
{

    function let(ServerRequestInterface $request)
    {
        $request->getQueryParams()->willReturn([
            'fields' => [
                'users' => 'name,email',
                'posts' => 'title,slug,comments',
                'comments' => 'title'
            ],
            'include' => ['users']
        ]);
        $this->beConstructedWith($request);
    }

    function its_initializable()
    {
        $this->shouldBeAnInstanceOf(SparseFields::class);
    }

    function it_can_determine_if_it_has_fields(ServerRequestInterface $request)
    {
        $this->hasFields()->shouldBe(true);
    }

    function it_can_return_fields_of_a_given_type()
    {
        $this->fieldsFor('users')->shouldBe(['name', 'email']);
    }

    function it_can_filter_out_an_array_of_attributes()
    {
        $this->filterFields('comments', ['title' => 'title', 'description' => 'description'])
            ->shouldBe(['title' => 'title']);
        $this->filterFields('publications', ['foo' => 'bar'])->shouldBe([]);
    }

    function it_returns_all_attributes_when_no_fields_is_set(ServerRequestInterface $request)
    {
        $request->getQueryParams()->willReturn([]);
        $this->beConstructedWith($request);
        $attributes = ['title' => 'title', 'description' => 'description'];
        $this->filterFields('comments', $attributes)->shouldBe($attributes);
    }

    function it_can_check_if_a_given_resource_should_be_included()
    {
        $this->includeResource('posts')->shouldBe(true);
        $this->includeResource('publications')->shouldBe(false);
    }

    function it_Can_check_if_a_given_field_should_be_included()
    {
        $this->includeField('name', 'users')->shouldBe(true);
        $this->includeField('age', 'users')->shouldBe(false);
    }

    function it_has_a_list_of_included_types()
    {
        $this->includedTypes()->shouldBe(['users']);
    }
}
