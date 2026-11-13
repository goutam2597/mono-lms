<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_unmatched_route_renders_branded_404_page(): void
    {
        $response = $this->get('/this-page-does-not-exist');

        $response->assertStatus(404);
        $response->assertSee(__('errors.codes.404.title'));
        $response->assertSee(__('errors.back_home'));
        $response->assertSee(route('home'), false);
    }

    public function test_get_request_on_post_route_renders_branded_405_page(): void
    {
        // checkout/{course:slug} only accepts POST, so GET on this URI 405s.
        $response = $this->get('/checkout/pay-offline');

        $response->assertStatus(405);
        $response->assertSee(__('errors.codes.405.title'));
    }

    public function test_every_error_view_renders_with_status_specific_copy(): void
    {
        foreach ([403, 404, 405, 419, 429, 500, 503] as $code) {
            $this->view("errors.{$code}")->assertSee(__("errors.codes.{$code}.title"));
        }

        // Fallback views pick up the generic copy for uncovered codes.
        $this->view('errors.4xx')->assertSee(__('errors.fallback.title'));
        $this->view('errors.5xx')->assertSee(__('errors.fallback.title'));
    }
}
