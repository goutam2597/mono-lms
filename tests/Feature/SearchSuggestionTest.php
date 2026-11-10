<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchSuggestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_suggestions_returns_empty_when_query_is_too_short(): void
    {
        $response = $this->getJson(route('api.search.suggestions', ['q' => 'a']));

        $response->assertOk()
            ->assertJson([
                'courses' => [],
                'categories' => [],
                'instructors' => [],
            ]);
    }

    public function test_search_suggestions_returns_matching_published_courses_categories_and_instructors(): void
    {
        $instructor = Instructor::create([
            'name' => 'Python Guru',
            'email' => 'guru@test.dev',
            'password' => 'password',
            'designation' => 'Lead Python Architect',
            'status' => 'active',
        ]);

        $inactiveInstructor = Instructor::create([
            'name' => 'Python Ghost',
            'email' => 'ghost@test.dev',
            'password' => 'password',
            'designation' => 'Python Trainee',
            'status' => 'inactive',
        ]);

        $category = Category::create([
            'name' => 'Python Programming',
            'slug' => 'python-programming',
            'sort_order' => 1,
        ]);

        $publishedCourse = Course::create([
            'title' => 'Master Python 3 from Scratch',
            'slug' => 'master-python-3-from-scratch',
            'subtitle' => 'Learn Python basics to advanced',
            'description' => 'Comprehensive Python programming guide',
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'price' => 29.99,
            'old_price' => 59.99,
            'is_published' => true,
        ]);

        $draftCourse = Course::create([
            'title' => 'Unpublished Python Secrets',
            'slug' => 'unpublished-python-secrets',
            'subtitle' => 'Draft course',
            'description' => 'Secret Python details',
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'price' => 99.00,
            'is_published' => false,
        ]);

        $response = $this->getJson(route('api.search.suggestions', ['q' => 'Python']));

        $response->assertOk();

        // 1. Published course returned, draft excluded
        $response->assertJsonFragment([
            'name' => 'Master Python 3 from Scratch',
            'url' => route('courses.show', 'master-python-3-from-scratch'),
            'price' => '$29.99',
            'old_price' => '$59.99',
            'category' => 'Python Programming',
            'instructor' => 'Python Guru',
        ]);
        $response->assertJsonMissing([
            'name' => 'Unpublished Python Secrets',
        ]);

        // 2. Matching category returned with count
        $response->assertJsonFragment([
            'name' => 'Python Programming',
            'slug' => 'python-programming',
            'count' => 1,
        ]);

        // 3. Active instructor returned, inactive excluded
        $response->assertJsonFragment([
            'name' => 'Python Guru',
            'designation' => 'Lead Python Architect',
        ]);
        $response->assertJsonMissing([
            'name' => 'Python Ghost',
        ]);
    }

    public function test_storefront_inputs_have_search_suggestion_attributes(): void
    {
        $home = $this->get('/');
        $home->assertOk();
        $home->assertSee('data-suggest="'.route('api.search.suggestions').'"', false);
        $home->assertSee('data-suggest-container="#global-search-bar form"', false);
        $home->assertSee('searchSuggestI18n', false);

        $catalog = $this->get(route('courses.index'));
        $catalog->assertOk();
        $catalog->assertSee('data-suggest="'.route('api.search.suggestions').'"', false);
        $catalog->assertSee('data-suggest-container=".relative"', false);
    }
}
