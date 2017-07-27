<?php

namespace App\Http\Controllers\Admin;

use App\Term;

class TermsTest extends \TestCase
{
    /** @test **/
    public function it_lists_the_terms_by_taxonomy()
    {
        $this->logInAsAdmin();
        $category = factory(Term::class)->create(['taxonomy' => 'category']);
        $tag = factory(Term::class)->create(['taxonomy' => 'tag']);

        $response = $this->get('/admin/terms/categories');
        $this->assertContains('Categories', $response->getContent());
        $this->assertContains($category->term, $response->getContent());
        $this->assertNotContains($tag->term, $response->getContent());
    }

    /** @test **/
    public function it_lists_categories()
    {
        $this->logInAsAdmin();

        $category = factory('App\Term')->create([
          'taxonomy' => 'category',
          'term'     => 'homeparty',
          ]);

        $response = $this->get('/admin/categories');
        $this->assertContains('homeparty', $response->getContent());
    }

    /** @test **/
    public function it_can_delete_a_term()
    {
        $this->logInAsAdmin();

        $category = factory('App\Term')->create([
          'taxonomy' => 'category',
          'term'     => 'nasty cat',
          ]);

        $this->assertDatabaseHas('terms', ['term' => 'nasty cat', 'taxonomy' => 'category']);

        $response = $this->delete("/admin/terms/{$category->id}");

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseMissing('terms', ['term' => 'nasty cat', 'taxonomy' => 'category']);
    }

    /** @test **/
    public function it_can_edit_a_term()
    {
        $this->logInAsAdmin();

        $category = factory('App\Term')->create([
          'taxonomy' => 'category',
          'term'     => 'Nasty Cat',
          ]);

        $this->get("/admin/terms/{$category->id}/edit");

        $response = $this->followRedirects($this->patch("/admin/terms/{$category->id}", [
          'term' => 'Nice Cat',
          ]));
        $response->assertSee('Category updated');

        $this->assertDatabaseHas('terms', ['taxonomy' => 'category', 'term'     => 'Nice Cat']);
    }

    /** @test **/
    public function it_cannot_save_a_term_that_already_exists()
    {
        $this->logInAsAdmin();

        $category_1 = factory('App\Term')->create([
          'taxonomy' => 'category',
          'term'     => 'Nasty Cat',
          ]);

        $category_2 = factory('App\Term')->create([
          'taxonomy' => 'category',
          'term'     => 'Nice Cat',
          ]);

        $this->get("/admin/terms/{$category_1->id}/edit");
        $response = $this->followRedirects($this->patch("/admin/terms/{$category_1->id}", [
          'term' => $category_2->term,
          ]));
        // ->type($category_2->term, 'term')
        // ->press('submit')
        $response->assertSee('already been taken');

        $this->assertDatabaseMissing('terms', ['id' => $category_1, 'taxonomy' => 'category', 'term' => 'Nice Cat']);
    }
}
