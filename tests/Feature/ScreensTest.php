<?php

namespace Tests\Feature;

use Tests\TestCase;

class ScreensTest extends TestCase
{
    /**
     * Test that all Dreams LMS screens load successfully.
     */
    public function test_all_screens_return_successful_response(): void
    {
        $routes = [
            '/',
            '/home-5',
            '/screens',
            '/courses/grid',
            '/courses/list',
            '/courses/detail',
            '/instructors/grid',
            '/instructors/list',
            '/instructors/detail',
            '/student/dashboard',
            '/student/profile',
            '/student/enrolled-courses',
            '/student/certificates',
            '/student/quiz-attempts',
            '/instructor/dashboard',
            '/instructor/courses',
            '/instructor/reviews',
            '/login',
            '/register',
            '/forgot-password',
            '/otp',
            '/set-password',
            '/lock-screen',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200, "Route [{$route}] failed to return 200 OK.");
        }
    }
}
