<?php

// Transactional emails.
return [
    'panels' => [
        'student' => 'Student',
        'instructor' => 'Instructor',
        'admin' => 'Admin',
    ],

    'reset' => [
        'subject' => 'Reset Your Password',
        'greeting' => 'Hello :name,',
        'line' => 'You are receiving this email because we received a password reset request for your account.',
        'action' => 'Reset Password',
        'expire' => 'This link will expire in :minutes minutes.',
        'closing' => 'If you did not request a password reset, no further action is required.',
        'panel_line' => 'Account type: :panel',
    ],
];
