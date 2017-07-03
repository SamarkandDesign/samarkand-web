<?php

namespace App\Http\Controllers\Api;

use App\Term;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;

class TermsControllerTest extends \TestCase
{
    use DatabaseTransactions;

    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = $this->logInAsAdmin();
    }

    /** @test **/
    public function it_saves_a_new_term_in_the_database()
    {
        $payload = [
            'term'     => 'pa laa',
            'taxonomy' => 'product_category',
        ];

        $response = $this->post('api/terms', $payload);
        $response->assertJson(['term' => 'pa laa']);

        $this->assertDatabaseHas('terms', $payload);
    }

    /** @test **/
    public function it_sanitizes_taxonomies()
    {
        $payload = [
            'term'     => 'Very Tall',
            'taxonomy' => 'Tree Height',
        ];

        $response = $this->post('api/terms', $payload);
        $response->assertJson(['term' => 'Very Tall']);

        $this->assertDatabaseHas('terms', ['taxonomy' => 'tree_height', 'term' => 'Very Tall']);
    }

    /** @test **/
    public function it_gets_terms()
    {
        $terms = factory(Term::class, 5)->create(['taxonomy' => 'product_category']);

        $response = $this->get(route('api.terms', 'product_category'));
        $response->assertJsonFragment(['term' => $terms[0]->term]);
    }

    /** @test **/
    public function it_does_not_allow_saving_duplicate_terms_of_a_certain_taxonomy()
    {
        $payload = [
            'term'     => 'Humungous',
            'taxonomy' => 'Lampshade Size',
        ];
        $response = $this->post('api/terms', $payload);
        $response->assertStatus(201);

        // adjust the taxonomy to just send the snake case version
        $payload = [
            'term'     => 'humungous',
            'taxonomy' => 'lampshade_size',
        ];

        $response = $this->json('POST', 'api/terms', $payload);

        $response->assertJsonFragment(['The term has already been taken.']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test **/
    public function it_deletes_a_term_from_storage()
    {
        $term = factory(Term::class)->create();

        $response = $this->delete("api/terms/{$term->id}");

        $this->assertDatabaseMissing('terms', ['taxonomy' => $term->taxonomy, 'term' => $term->term]);
    }

    /** @test */
    public function it_updates_a_term()
    {
        $term = factory(Term::class)->create();

        $response = $this->patch("api/terms/{$term->id}", ['order' => 11]);

        $this->assertDatabaseHas('terms', ['taxonomy' => $term->taxonomy, 'term' => $term->term, 'order' => 11]);
    }
}
