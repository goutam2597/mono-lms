<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Dreams LMS
|--------------------------------------------------------------------------
|
| All screen routes mapped from the Figma UI Kit:
| - Home Page 4 (Selected as default home screen per user instruction)
| - Home Page 1 & Home Page 2
| - Screens Showcase Gallery (/screens)
| - Courses (Grid, List, Details)
| - Instructors (Grid, List, Details)
| - Student Dashboard (Dashboard, Profile, Enrolled Courses, Certificates, Quiz Attempts)
| - Instructor Dashboard (Dashboard, Courses, Reviews)
| - Authentication (Login, Register, Forgot Password, OTP, Set Password, Lock Screen)
|
*/

// Home Routes - Default Home is Home Page 4
Route::get('/', function () {
    return view('pages.home-4');
})->name('home');

Route::get('/home-4', function () {
    return view('pages.home-4');
})->name('home-4');

Route::get('/home-1', function () {
    return view('pages.home');
})->name('home-1');

Route::get('/home-2', function () {
    return view('pages.home-2');
})->name('home-2');

Route::get('/home-5', function () {
    return view('pages.home-5');
})->name('home-5');

// Screens Showcase Hub
Route::get('/screens', function () {
    return view('screens.index');
})->name('screens');
Route::get('/screens-index', function () {
    return redirect()->route('screens');
})->name('screens.index');

// Course Pages
Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/grid', function () {
        return view('pages.courses.grid');
    })->name('grid');

    Route::get('/list', function () {
        return view('pages.courses.list');
    })->name('list');

    Route::get('/detail', function () {
        return view('pages.courses.detail');
    })->name('detail');
});

// Instructor Public Directory Pages
Route::prefix('instructors')->name('instructors.')->group(function () {
    Route::get('/grid', function () {
        return view('pages.instructors.grid');
    })->name('grid');

    Route::get('/list', function () {
        return view('pages.instructors.list');
    })->name('list');

    Route::get('/detail', function () {
        return view('pages.instructors.detail');
    })->name('detail');
});

// Student Dashboard Pages
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.student.dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('pages.student.profile');
    })->name('profile');

    Route::get('/enrolled-courses', function () {
        return view('pages.student.enrolled-courses');
    })->name('enrolled-courses');

    Route::get('/certificates', function () {
        return view('pages.student.certificates');
    })->name('certificates');

    Route::get('/quiz-attempts', function () {
        return view('pages.student.quiz-attempts');
    })->name('quiz-attempts');
});

// Instructor Dashboard Pages
Route::prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.instructor.dashboard');
    })->name('dashboard');

    Route::get('/courses', function () {
        return view('pages.instructor.courses');
    })->name('courses');

    Route::get('/reviews', function () {
        return view('pages.instructor.reviews');
    })->name('reviews');
});

// Authentication Pages
Route::get('/login', function () {
    return view('pages.auth.login');
})->name('login');

Route::get('/register', function () {
    return view('pages.auth.register');
})->name('register');

Route::get('/forgot-password', function () {
    return view('pages.auth.forgot-password');
})->name('forgot-password');

Route::get('/otp', function () {
    return view('pages.auth.otp');
})->name('otp');

Route::get('/set-password', function () {
    return view('pages.auth.set-password');
})->name('set-password');

Route::get('/lock-screen', function () {
    return view('pages.auth.lock-screen');
})->name('lock-screen');
