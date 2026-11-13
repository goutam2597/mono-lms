<?php

// Public error pages (resources/views/errors) keyed by HTTP status code.
return [
    'back_home' => 'Back to Home',
    'browse_courses' => 'Browse Courses',

    'codes' => [
        '403' => [
            'title' => 'Access Denied',
            'description' => 'You do not have permission to view this page. Try signing in with the right account, or contact us if you think this is a mistake.',
        ],
        '404' => [
            'title' => 'Page Not Found',
            'description' => 'The page you are looking for does not exist or may have been moved. Let us get you back on track.',
        ],
        '405' => [
            'title' => 'Method Not Allowed',
            'description' => 'This page does not accept that type of request. Check the link you followed and try again.',
        ],
        '419' => [
            'title' => 'Page Expired',
            'description' => 'Your session expired for security reasons. Go back, refresh the page, and try again.',
        ],
        '429' => [
            'title' => 'Too Many Requests',
            'description' => 'You are moving a little fast. Please wait a moment and try again.',
        ],
        '500' => [
            'title' => 'Server Error',
            'description' => 'Something went wrong on our side. Our team has been notified; please try again in a moment.',
        ],
        '503' => [
            'title' => 'Under Maintenance',
            'description' => 'We are performing scheduled maintenance. Please check back shortly.',
        ],
    ],

    'fallback' => [
        'title' => 'Unexpected Error',
        'description' => 'The request could not be completed. Please try again in a moment.',
    ],
];
