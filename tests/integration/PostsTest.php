<?php

namespace Unit;

use TestCase;
use App\Repositories\Post\DbPostRepository;

class PostsTest extends TestCase
{
    protected $posts;

    public function setUp()
    {
        parent::setUp();
        $this->posts = new DbPostRepository(new \App\Post());
    }

    public function testHomePageWorks()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetPostsForUser()
    {
        $user = factory('App\User')->create();

        factory('App\Post', 2)->create(['user_id' => $user->id]);
        factory('App\Post', 3)->create();

        $posts = $this->posts->getByUserId($user->id);
        $this->assertCount(2, $posts);
    }

    public function testGetSinglePosts()
    {
        $postDummy = factory('App\Post')->create();

        $post = $this->posts->fetch($postDummy->id);
        $this->assertEquals($post->slug, $postDummy->slug);

        $postDummy = factory('App\Post')->create();
        $post = $this->posts->getBySlug($postDummy->slug);
        $this->assertEquals($post->slug, $postDummy->slug);
    }

    /** @test **/
    public function it_shows_a_list_of_posts()
    {
        $post1 = factory('App\Post')->create();
        $post2 = factory('App\Post')->create();
        $post3 = factory('App\Post')->create(['status' => 'draft']);

        $response = $this->get('/posts');

        $this->assertContains($post1->title, $response->getContent());
        $this->assertContains($post2->title, $response->getContent());
        $this->assertNotContains($post3->title, $response->getContent());
    }

    /** @test **/
    public function it_shows_a_single_post()
    {
        $post = factory('App\Post')->create();
        $postMY = $post->dateForUrl;

        $response = $this->get("/posts/$postMY/{$post->slug}");

        $this->assertContains($post->title, $response->getContent());
        $this->assertContains($post->content, $response->getContent());
    }

    /** @test **/
    public function it_does_not_show_a_draft_post()
    {
        $post = factory('App\Post')->create(['status' => 'draft']);
        $postMY = $post->dateForUrl;

        $response = $this->get("/posts/$postMY/{$post->slug}");

        $this->assertNotContains($post->title, $response->getContent());
    }
}
