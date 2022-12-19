<?php

/**
 * This file is part of slick/json-api package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\JSONAPI\Object\SchemaDiscover;

use Slick\JSONAPI\Object\SchemaDiscover;
use Slick\JSONAPI\Object\SchemaDiscover\AttributeSchemaDiscover;
use PhpSpec\ObjectBehavior;
use spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes\Comment;
use spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes\Post;
use spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes\PostList;
use spec\Slick\JSONAPI\Object\SchemaDiscover\Attributes\User;
use function Composer\Autoload\includeFile;

includeFile(__DIR__.'/Attributes/User.php');
includeFile(__DIR__.'/Attributes/Post.php');
includeFile(__DIR__.'/Attributes/PostList.php');
includeFile(__DIR__.'/Attributes/Comment.php');

/**
 * AttributeSchemaDiscoverSpec specs
 *
 * @package spec\Slick\JSONAPI\Object\SchemaDiscover
 */
class AttributeSchemaDiscoverSpec extends ObjectBehavior
{
    private ?Post $post;

    private ?PostList $postList;

    function let()
    {
        $owner = new User('John Doe');
        $this->post = new Post($owner, 'some tile', 'some body');
        $this->post->comments()->add(
            new Comment($this->post, $owner, 'a test comment')
        );
        $this->post->comments()->add(
            new Comment($this->post, $owner, 'a second comment')
        );
        $this->postList = new PostList();
        $this->postList->posts()->add($this->post);

    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AttributeSchemaDiscover::class);
    }

    function its_a_schema_discover()
    {
        $this->shouldBeAnInstanceOf(SchemaDiscover::class);
    }

    function it_can_discover_a_class_schema()
    {
        $this->isConvertible($this->post)->shouldBe(true);
        $this->isConvertible($this->postList)->shouldBe(true);
    }

    function it_can_discover_a_schema_from_an_object()
    {
        $this->discover($this->post)->shouldBeAnInstanceOf(SchemaDiscover\AttributeSchema::class);
        $this->discover($this->postList)->shouldBeAnInstanceOf(SchemaDiscover\AttributeResourceCollectionSchema::class);
    }
}