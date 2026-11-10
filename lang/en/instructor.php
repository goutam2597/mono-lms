<?php

// Instructor panel.
return [
    'panel' => 'Instructor Panel',

    'nav' => [
        'dashboard' => 'Dashboard',
        'courses' => 'My Courses',
        'students' => 'Students',
        'reviews' => 'Reviews',
        'profile' => 'My Profile',
        'tickets' => 'Help & Support',
        'settings' => 'Settings',
        'logout' => 'Logout',
    ],

    'dashboard' => [
        'title' => 'Instructor Dashboard',
        'welcome' => 'Welcome back, :name',
        'revenue' => 'Total Revenue',
        'students' => 'Total Students',
        'courses' => 'My Courses',
        'rating' => 'Average Rating',
        'earnings_chart' => 'Enrollments (last 12 months)',
        'top_courses' => 'Top Performing Courses',
        'recent_students' => 'Recent Enrollments',
        'no_data' => 'No data yet. Create your first course!',
    ],

    'courses' => [
        'title' => 'My Courses',
        'create' => 'New Course',
        'edit' => 'Edit Course',
        'enrolled' => 'Enrolled',
        'lessons' => 'Lessons',
        'no_courses' => 'You have not created any courses yet.',
        'created' => 'Course created successfully.',
        'updated' => 'Course updated successfully.',
        'deleted' => 'Course deleted.',
        'not_owner' => 'You can only manage your own courses.',
        'fields' => [
            'title' => 'Course Title',
            'slug' => 'Slug (URL)',
            'subtitle' => 'Subtitle',
            'category' => 'Category',
            'description' => 'Description',
            'image' => 'Image URL',
            'price' => 'Price',
            'old_price' => 'Compare-at Price',
            'level' => 'Level',
            'is_published' => 'Published',
            'is_featured' => 'Featured',
        ],
    ],

    'lessons' => [
        'title' => 'Lessons',
        'add' => 'Add Lesson',
        'edit' => 'Edit Lesson',
        'no_lessons' => 'No lessons yet. Add the first one!',
        'created' => 'Lesson added.',
        'updated' => 'Lesson updated.',
        'deleted' => 'Lesson deleted.',
        'fields' => [
            'title' => 'Lesson Title',
            'duration' => 'Duration (minutes)',
            'video_url' => 'Video URL',
            'sort_order' => 'Order',
        ],
    ],

    'students' => [
        'title' => 'My Students',
        'subtitle' => 'Students enrolled in your courses',
        'course' => 'Course',
        'progress' => 'Progress',
        'enrolled_on' => 'Enrolled On',
        'no_students' => 'No enrollments yet.',
        'count' => ':count students',
    ],

    'reviews' => [
        'title' => 'Course Reviews',
        'no_reviews' => 'No reviews yet.',
        'count' => ':count reviews',
    ],

    'profile' => [
        'title' => 'My Profile',
        'updated' => 'Profile updated successfully.',
        'password' => 'Change Password',
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'new_password_hint' => 'Leave blank to keep your current password.',
        'password_updated' => 'Password changed successfully.',
        'current_password_incorrect' => 'The current password is incorrect.',
    ],
];
