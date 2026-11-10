<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\CertificateTemplate;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\Instructor;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Plan;
use App\Models\Post;
use App\Models\Review;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Student;
use App\Models\SupportTicket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the platform: languages, roles, demo accounts and the original
     * design-kit demo catalog (categories with brand icons, instructors with
     * photos, courses with the original demo images/prices).
     *
     * Seeded logins (all with password "password"):
     *  - superadmin@mono-lms.test   (super admin)
     *  - manager@mono-lms.test      (manager role)
     *  - instructor@mono-lms.test   (instructor panel — Rolands Granger)
     *  - student@mono-lms.test      (student panel)
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);

        $this->seedAdmins();
        $this->seedCatalog();
        $this->seedBlogAndPlans();
        $this->seedAdvancedLmsData();

        $this->command->info('Seeded logins (password: "password"):');
        $this->command->table(
            ['Panel', 'Email', 'Role'],
            [
                ['Admin', 'superadmin@mono-lms.test', 'Super Admin'],
                ['Admin', 'manager@mono-lms.test', 'Manager'],
                ['Instructor', 'instructor@mono-lms.test', '—'],
                ['Student', 'student@mono-lms.test', '—'],
            ]
        );
    }

    private function seedAdmins(): void
    {
        $superRole = Role::where('slug', 'super-admin')->first();
        $managerRole = Role::where('slug', 'manager')->first();

        Admin::updateOrCreate(
            ['email' => 'superadmin@mono-lms.test'],
            [
                'role_id' => $superRole?->id,
                'name' => 'Super Admin',
                'password' => 'password',
                'is_super_admin' => true,
                'status' => 'active',
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'admin@learnify.test'],
            [
                'role_id' => $superRole?->id,
                'name' => 'Administrator',
                'password' => '12345678',
                'is_super_admin' => true,
                'status' => 'active',
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'manager@mono-lms.test'],
            [
                'role_id' => $managerRole?->id,
                'name' => 'Platform Manager',
                'password' => 'password',
                'is_super_admin' => false,
                'status' => 'active',
            ]
        );

        Student::updateOrCreate(
            ['email' => 'student@mono-lms.test'],
            ['name' => 'Eugene Andre', 'password' => 'password', 'status' => 'active']
        );

        Student::updateOrCreate(
            ['email' => 'priya@mono-lms.test'],
            ['name' => 'Priya Sharma', 'password' => 'password', 'status' => 'active']
        );

        $demoStudents = [
            ['alice@mono-lms.test', 'Alice Student', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80'],
            ['ethan@mono-lms.test', 'Ethan Student', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80'],
            ['elena@mono-lms.test', 'Elena Rossi', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80'],
            ['marcus@mono-lms.test', 'Marcus Bell', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80'],
            ['hannah@mono-lms.test', 'Hannah Student', 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&auto=format&fit=crop&q=80'],
            ['diana@mono-lms.test', 'Diana Student', 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=150&auto=format&fit=crop&q=80'],
            ['julia@mono-lms.test', 'Julia Student', 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=150&auto=format&fit=crop&q=80'],
            ['fatima@mono-lms.test', 'Fatima Zahra', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80'],
        ];

        foreach ($demoStudents as [$sEmail, $sName, $sAvatar]) {
            Student::updateOrCreate(
                ['email' => $sEmail],
                ['name' => $sName, 'password' => 'password', 'avatar' => $sAvatar, 'status' => 'active']
            );
        }
    }

    private function seedCatalog(): void
    {
        // ---- Categories: the five branded cards from the design kit first,
        // ---- then the extra categories used by the demo course cards.
        $categories = [
            ['Angular Development', 'angular-development', '#dd0031', 'M12 2.5L2.8 5.75L4.2 17.5L12 21.8L19.8 17.5L21.2 5.75L12 2.5ZM12 4.67L18.7 7.05L17.57 16.55L12 19.64L6.43 16.55L5.3 7.05L12 4.67ZM12 6.8L8.6 15.2H10.4L11.1 13.4H12.9L13.6 15.2H15.4L12 6.8ZM12 9.4L12.5 11.8H11.5L12 9.4Z', 1],
            ['Python Development', 'python-development', '#3776ab', 'M11.9 2C8.3 2 6 3.6 6 5.8V7.5H12V8.4H3.7C2.1 8.4 1 9.7 1 11.5C1 13.5 2.2 14.8 4 14.8H5.6V13.3C5.6 11.9 6.8 10.7 8.2 10.7H13.6C14.8 10.7 15.8 9.7 15.8 8.5V5.5C15.8 3.5 14.2 2 11.9 2ZM9.5 4C10.1 4 10.6 4.5 10.6 5.1C10.6 5.7 10.1 6.2 9.5 6.2C8.9 6.2 8.4 5.7 8.4 5.1C8.4 4.5 8.9 4 9.5 4ZM12.1 22C15.7 22 18 20.4 18 18.2V16.5H12V15.6H20.3C21.9 15.6 23 14.3 23 12.5C23 10.5 21.8 9.2 20 9.2H18.4V10.7C18.4 12.1 17.2 13.3 15.8 13.3H10.4C9.2 13.3 8.2 14.3 8.2 15.5V18.5C8.2 20.5 9.8 22 12.1 22ZM14.5 20C13.9 20 13.4 19.5 13.4 18.9C13.4 18.3 13.9 17.8 14.5 17.8C15.1 17.8 15.6 18.3 15.6 18.9C15.6 19.5 15.1 20 14.5 20Z', 2],
            ['NodeJS Development', 'nodejs-development', '#339933', 'M12 2L3.5 6.9V17.1L12 22L20.5 17.1V6.9L12 2ZM11.1 18.5V11.2L6 14.1V9.5L11.1 6.5V5.5L4.8 9.1V16.3L11.1 19.9V18.5ZM12.9 5.5L19.2 9.1V16.3L12.9 19.9V18.5L18 15.6V10.9L12.9 8V5.5Z', 3],
            ['PHP Development', 'php-development', '#777bb4', 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-5 13.5l1.2-6h2.4c1.1 0 1.9.9 1.9 2s-.9 2-1.9 2H9.2l-.4 2H7zm8.8 0l1.2-6h2.4c1.1 0 1.9.9 1.9 2s-.9 2-1.9 2h-1.4l-.4 2h-1.8zm-5.4-2l.4-2h1.4c.5 0 .8.3.8.8s-.3.8-.8.8h-1.4zm6.4 0l.4-2h1.4c.5 0 .8.3.8.8s-.3.8-.8.8h-1.4z', 4],
            ['Laravel Development', 'laravel-development', '#ff2d20', 'M12 2L3 6.5V17.5L12 22L21 17.5V6.5L12 2ZM19.2 16.4L12 20L4.8 16.4V7.6L12 4L19.2 7.6V16.4Z', 5],
            ['Web Design', 'web-design', '#0dcaf0', '🎨', 6],
            ['Business Management', 'business-management', '#ffc107', '💼', 7],
            ['Creative Arts & Media', 'creative-arts-media', '#ff4667', '🎬', 8],
            ['Personalized Learning', 'personalized-learning', '#03c95a', '📖', 9],
            ['Docker Development', 'docker-development', '#2496ed', '🐳', 10],
        ];

        $categories = collect($categories)->mapWithKeys(function ($c) {
            $category = Category::updateOrCreate(
                ['slug' => $c[1]],
                ['name' => $c[0], 'color' => $c[2], 'icon' => $c[3], 'sort_order' => $c[4]]
            );

            return [$c[1] => $category];
        });

        // ---- Instructors: the eight demo mentors with their photos.
        $instructors = [
            ['instructor@mono-lms.test', 'Rolands Granger', 'Senior Full-Stack Engineer & Architect', 'photo-1507003211169-0a1dd7228f2d', 'Teaching web architecture, PHP, Laravel, Docker, and modern reactive frontends with over 10 years of production experience.'],
            ['david@mono-lms.test', 'David Benitez', 'Principal Product Designer & Design Systems Certified', 'photo-1500648767791-00dcc994a43e', 'Former design lead at high-growth startups, coaching digital designers on building design systems and seamless UX flows.'],
            ['hinata@mono-lms.test', 'Hinata Hyuga', 'Machine Learning & Python Specialist', 'photo-1544005313-94ddf0286df2', 'Specializing in deep learning, neural networks, computer vision, and big data pipelines for AI-driven applications.'],
            ['keny@mono-lms.test', 'Keny White', 'Cloud Systems Architect', 'photo-1519085360753-af0119f7cbe7', 'Designing resilient cloud infrastructure and teaching DevOps practices used by enterprise teams.'],
            ['nicole@mono-lms.test', 'Nicole Brown', 'Strategic Business Coach', 'photo-1573496359142-b8d87734a5a2', 'Helping founders and executives turn strategy into measurable growth for over a decade.'],
            ['john@mono-lms.test', 'John Doe', 'Creative Director & Artist', 'photo-1534528741775-53994a69daeb', 'Award-winning creative director blending storytelling, motion, and digital art for global brands.'],
            ['sarah.jenkins@mono-lms.test', 'Sarah Jenkins', 'Cybersecurity Specialist', 'photo-1580489944761-15a19d654956', 'Security researcher and educator focused on practical defense, pentesting, and secure coding habits.'],
            ['michael@mono-lms.test', 'Michael Chang', 'Data Architect', 'photo-1492562080023-ab3db95bfbce', 'Building large-scale data platforms and teaching modern analytics engineering.'],
        ];

        foreach ($instructors as [$email, $name, $designation, $photo, $bio]) {
            Instructor::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => 'password',
                    'designation' => $designation,
                    'bio' => $bio,
                    'avatar' => 'https://images.unsplash.com/'.$photo.'?w=600&auto=format&fit=crop&q=80',
                    'status' => 'active',
                    'verification_status' => 'approved',
                    'verified_at' => now(),
                ]
            );
        }

        $rolands = Instructor::where('email', 'instructor@mono-lms.test')->first();
        $david = Instructor::where('email', 'david@mono-lms.test')->first();
        $hinata = Instructor::where('email', 'hinata@mono-lms.test')->first();

        // ---- Courses: the original demo cards (title, category, price,
        // ---- compare-at price, duration, image) straight from the design kit.
        $courses = [
            ['Build Responsive Websites with HTML', 'web-design', $rolands, 650.00, 900.00, 510, 'photo-1522202176988-66273c2fd55f', true, true],
            ['The Complete Web Developer Python Course', 'python-development', $hinata, 300.00, null, 390, 'photo-1526374965328-7f61d4dc18c5', true, true],
            ['The Complete Business Management Course', 'business-management', $rolands, 200.00, 900.00, 390, 'photo-1454165804606-c3d57bc86b40', true, false],
            ['Build Creative Arts & Media Course', 'creative-arts-media', $david, 700.00, 900.00, 390, 'photo-1513364776144-60967b0f800f', true, false],
            ['Build Responsive Websites with Angular', 'angular-development', $rolands, 550.00, null, 250, 'photo-1517245386807-bb43f82c33c4', true, true],
            ['Critical Reading and Writing Skills', 'personalized-learning', $david, 0, null, 195, 'photo-1499750310107-5fef28a66643', true, false],
            ['NodeJS Fundamentals: Storage in Server', 'nodejs-development', $rolands, 999.00, null, 270, 'photo-1555066931-4365d14bab8c', true, true],
            ['Data Science Essentials: Exploring Docker', 'docker-development', $hinata, 6965.00, null, 510, 'photo-1551288049-bebda4e38f71', true, false],
            ['Complete Web Developer PHP Framework Course 2.0', 'php-development', $rolands, 450.00, 800.00, 420, 'photo-1519085360753-af0119f7cbe7', false, false],
            ['Marketing Strategies for Business Growth', 'business-management', $david, 350.00, 600.00, 300, 'photo-1494790108377-be9c29b29330', true, false],
            ['Business: From Idea to Launch', 'business-management', $nicole = Instructor::where('email', 'nicole@mono-lms.test')->first(), 280.00, null, 240, 'photo-1500648767791-00dcc994a43e', true, false],
            ['Information About UI/UX Design Degree & Professional Career', 'web-design', $david, 120.00, 220.00, 330, 'photo-1534528741775-53994a69daeb', true, true],
        ];

        $eugene = Student::where('email', 'student@mono-lms.test')->first();
        $priya = Student::where('email', 'priya@mono-lms.test')->first();

        foreach ($courses as $i => [$title, $categorySlug, $instructor, $price, $oldPrice, $minutes, $photo, $published, $featured]) {
            $course = Course::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'instructor_id' => $instructor?->id ?? $rolands->id,
                    'category_id' => $categories[$categorySlug]->id,
                    'title' => $title,
                    'subtitle' => 'Master '.Str::lower($title).' with hands-on projects and expert guidance.',
                    'description' => 'This comprehensive course takes you from fundamentals to advanced practice. You will build real projects, get feedback from your instructor, and finish with a portfolio-ready skill set — exactly as designed in the Mono LMS design kit.',
                    'image' => 'https://images.unsplash.com/'.$photo.'?w=600&auto=format&fit=crop&q=80',
                    'price' => $price,
                    'old_price' => $oldPrice,
                    'level' => match (true) {
                        $price > 900 => 'advanced', $price > 400 => 'intermediate', default => 'beginner'
                    },
                    'duration_minutes' => $minutes,
                    'is_published' => $published,
                    'is_featured' => $featured,
                    'is_trending' => in_array($i, [0, 1, 2, 4, 6, 10]),
                ]
            );

            $lessons = ['Introduction & Course Roadmap', 'Core Concepts Walkthrough', 'Hands-on Project Build', 'Wrap-up & Next Steps'];
            foreach ($lessons as $j => $lessonTitle) {
                $course->lessons()->updateOrCreate(
                    ['title' => $lessonTitle],
                    ['duration_minutes' => intdiv($minutes, 4), 'sort_order' => $j + 1]
                );
            }

            if ($i < 3 && $eugene) {
                $progress = [65, 100, 25][$i];
                Enrollment::updateOrCreate(
                    ['student_id' => $eugene->id, 'course_id' => $course->id],
                    ['progress' => $progress, 'status' => $progress === 100 ? 'completed' : 'active']
                );
            }
            if ($i < 2 && $priya) {
                Enrollment::updateOrCreate(
                    ['student_id' => $priya->id, 'course_id' => $course->id],
                    ['progress' => [40, 80][$i], 'status' => 'active']
                );
            }

            // Distribute demo enrollments across other demo students
            $otherStudents = Student::whereNotIn('id', [$eugene?->id ?? 0, $priya?->id ?? 0])->get();
            if ($otherStudents->isNotEmpty()) {
                $studentForCourse = $otherStudents[$i % $otherStudents->count()];
                Enrollment::updateOrCreate(
                    ['student_id' => $studentForCourse->id, 'course_id' => $course->id],
                    ['progress' => ($i * 15 + 20) % 100, 'status' => 'active']
                );
            }

            if ($i < 2 && $eugene) {
                Review::updateOrCreate(
                    ['student_id' => $eugene->id, 'course_id' => $course->id],
                    ['rating' => 5 - $i, 'comment' => 'Excellent course, clearly explained and well structured.', 'status' => 'approved']
                );
            }
            if ($i === 2 && $priya) {
                Review::updateOrCreate(
                    ['student_id' => $priya->id, 'course_id' => $course->id],
                    ['rating' => 5, 'comment' => 'Very informative and practical! Would definitely recommend to my peers.', 'status' => 'pending']
                );
            }
        }
    }

    private function seedBlogAndPlans(): void
    {
        // Membership is admin-dependent: the super admin can switch it off
        // from Admin → Membership Plans.
        Setting::set('membership_enabled', '1');

        $plans = [
            ['Basic', 19.00, 'monthly', 'Start learning with the essentials.', ['Mobile & desktop learning', 'Progress tracking'], false, 1],
            ['Standard', 39.00, 'monthly', 'Everything you need to master new skills.', ['Unlimited course access', 'Mobile & desktop learning', 'Progress tracking', 'Downloadable resources', 'Certificates of completion', 'Priority support'], true, 2],
            ['Annual Premium', 299.00, 'yearly', 'Best value — two months free, billed yearly.', ['Unlimited course access', 'Mobile & desktop learning', 'Progress tracking', 'Downloadable resources', 'Certificates of completion', 'Priority support', 'Offline downloads', '1-on-1 mentor sessions', 'Early access to new courses'], false, 3],
        ];

        foreach ($plans as [$name, $price, $period, $description, $features, $popular, $order]) {
            Plan::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'billing_period' => $period,
                    'features' => $features,
                    'is_active' => true,
                    'is_popular' => $popular,
                    'sort_order' => $order,
                ]
            );
        }

        $admin = Admin::where('email', 'superadmin@mono-lms.test')->first();

        $posts = [
            ['The Impact of LMS on Academic Journey Education', 'photo-1523240795612-9a054b0db644', 'A deep dive into how modern learning management systems reshape the academic journey for students and institutions alike.', 'From streamlined course delivery to data-driven progress tracking, learning management systems have quietly transformed how education is delivered. In this article we explore the measurable impact of LMS adoption on student outcomes, engagement, and institutional efficiency.'],
            ['Maximizing Academic Success with the Right LMS', 'photo-1454165804606-c3d57bc86b40', 'Choosing the right platform is half the battle. Here is what actually moves the needle for learners.', 'Not all learning platforms are created equal. The difference between a course catalog and a true learning experience comes down to structure, feedback loops, and motivation design. We break down the features that correlate with real academic success.'],
            ['Why Unlimited Access Beats Buying Course by Course', 'photo-1499750310107-5fef28a66643', 'The subscription model is changing how people learn online — and the numbers back it up.', 'Learners with unlimited memberships complete 3x more courses than those buying à la carte. When cost friction disappears, curiosity takes over. We look at the data behind Coursera-style memberships and what it means for your learning goals.'],
            ['How to Build a Learning Habit That Sticks', 'photo-1517245386807-bb43f82c33c4', 'Consistency beats intensity. Five evidence-based techniques to make daily learning automatic.', 'The most successful students are rarely the most talented — they are the most consistent. This article covers habit stacking, spaced repetition, and the 20-minute rule that turns casual learners into lifelong ones.'],
        ];

        foreach ($posts as $i => [$title, $photo, $excerpt, $content]) {
            Post::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'admin_id' => $admin?->id,
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $content."\n\n".str_repeat('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', 3),
                    'image' => 'https://images.unsplash.com/'.$photo.'?w=800&auto=format&fit=crop&q=80',
                    'status' => 'published',
                    'published_at' => now()->subDays($i * 5),
                ]
            );
        }
    }

    private function seedAdvancedLmsData(): void
    {
        // 1. Seed Real Orders from Enrollments
        $enrollments = Enrollment::with(['student', 'course'])->get();
        foreach ($enrollments as $idx => $enrollment) {
            $code = 'LRN-'.strtoupper(substr(md5((string) $enrollment->id.'mono-seed'), 0, 8));
            $price = (float) ($enrollment->course?->price ?? 0);
            Order::updateOrCreate(
                ['order_number' => $code],
                [
                    'student_id' => $enrollment->student_id,
                    'course_id' => $enrollment->course_id,
                    'amount' => $price,
                    'currency' => 'USD',
                    'payment_method' => ['stripe', 'paypal', 'manual'][$idx % 3],
                    'payment_status' => $idx === 0 ? 'pending' : ($idx === 1 ? 'refunded' : 'paid'),
                    'created_at' => now()->subDays($idx * 2),
                ]
            );
        }

        // 2. Seed Coupons
        $coupons = [
            ['WELCOME10', 'Welcome 10% Discount', 'percent', 10.00, 100, 14, now()->addMonths(3), true],
            ['SPRING2026', 'Spring Sale 25% Off', 'percent', 25.00, 50, 28, now()->addMonth(), true],
            ['MASTERCLASS50', 'Masterclass $50 Flat Discount', 'fixed', 50.00, 20, 8, now()->addDays(15), true],
            ['SUMMEREXP', 'Summer Early Bird (Expired)', 'percent', 15.00, 50, 50, now()->subDays(5), false],
        ];
        foreach ($coupons as [$cCode, $cTitle, $cType, $cVal, $cMax, $cUsed, $cExp, $cActive]) {
            Coupon::updateOrCreate(
                ['code' => $cCode],
                [
                    'title' => $cTitle,
                    'discount_type' => $cType,
                    'discount_value' => $cVal,
                    'max_uses' => $cMax,
                    'used_count' => $cUsed,
                    'expires_at' => $cExp,
                    'is_active' => $cActive,
                ]
            );
        }

        // 3. Seed Payouts
        $instructors = Instructor::all();
        if ($instructors->isNotEmpty()) {
            $payouts = [
                [$instructors->first()->id, 450.00, 'paypal', 'rolands.payout@gmail.com', 'pending', null, 'Pending monthly revenue share.'],
                [$instructors->skip(1)->first()?->id ?? $instructors->first()->id, 320.00, 'bank_transfer', 'US44BANK0099881122', 'pending', null, 'Direct deposit requested.'],
                [$instructors->first()->id, 1280.00, 'paypal', 'rolands.payout@gmail.com', 'processed', now()->subDays(14), 'Cleared batch #9842.'],
                [$instructors->skip(1)->first()?->id ?? $instructors->first()->id, 890.00, 'stripe', 'acct_1Mnm8920Kks8', 'processed', now()->subMonth(), 'Cleared payout.'],
            ];
            foreach ($payouts as [$pInstId, $pAmt, $pMethod, $pAcc, $pStatus, $pProc, $pNotes]) {
                Payout::updateOrCreate(
                    ['instructor_id' => $pInstId, 'amount' => $pAmt, 'payout_account' => $pAcc],
                    [
                        'payment_method' => $pMethod,
                        'status' => $pStatus,
                        'processed_at' => $pProc,
                        'notes' => $pNotes,
                    ]
                );
            }
        }

        // 4. Seed Certificate Templates
        $certTemplates = [
            ['Accredited Professional Diploma', 'classic', 'Program Director', 'Dr. Alexander Wright', '#ffffff', '#344c3d', true],
            ['Executive Modern Completion Award', 'modern', 'Dean of Academic Affairs', 'Sarah Jenkins, Ph.D.', '#f8fafc', '#0284c7', false],
            ['Full-Stack Engineering Masterclass', 'elegant', 'Lead System Architect', 'Rolands Granger', '#ffffff', '#dd0031', false],
        ];
        foreach ($certTemplates as [$ctTitle, $ctType, $ctSig, $ctName, $ctBg, $ctBorder, $ctDef]) {
            CertificateTemplate::updateOrCreate(
                ['title' => $ctTitle],
                [
                    'template_type' => $ctType,
                    'signature_title' => $ctSig,
                    'signer_name' => $ctName,
                    'bg_color' => $ctBg,
                    'border_color' => $ctBorder,
                    'is_default' => $ctDef,
                ]
            );
        }

        // 5. Seed Support Tickets
        $tickets = [
            ['TCK-8021', 'Eugene Andre', 'student@mono-lms.test', 'Course video buffering on lesson 3', 'high', 'open', 'When opening lesson 3 on modern browser, video buffers at 0:45. Could you check server bandwidth?'],
            ['TCK-7840', 'Priya Sharma', 'priya@mono-lms.test', 'Question regarding invoice receipt for tax VAT', 'medium', 'in_progress', 'Hi, I need our company VAT ID added to our invoice #LRN-SERULLNZ.'],
            ['TCK-6912', 'Alice Student', 'alice@mono-lms.test', 'Certificate name spelling correction', 'low', 'resolved', 'My certificate had a small spelling typo in middle name. Resolved with updated record.'],
        ];
        foreach ($tickets as [$tNum, $tName, $tEmail, $tSub, $tPrio, $tStat, $tMsg]) {
            SupportTicket::updateOrCreate(
                ['ticket_number' => $tNum],
                [
                    'user_name' => $tName,
                    'user_email' => $tEmail,
                    'subject' => $tSub,
                    'priority' => $tPrio,
                    'status' => $tStat,
                    'message' => $tMsg,
                ]
            );
        }

        // 6. Seed Contact Messages
        $messages = [
            ['Marcus Vance', 'marcus.vance@company.com', '+1 555-0192', 'Enterprise LMS licensing for 250 engineers', 'Hello! We are looking to enroll our tech team in your full-stack and cloud tracks. Can we schedule a quick demo?'],
            ['Elena Rostova', 'elena@academy.eu', '+44 20 7946 0991', 'Partnership inquiry for university students', 'We would love to integrate your certification curriculum into our graduate school courses.'],
            ['David Kim', 'dkim@startuplab.io', null, 'Custom API webhooks integration', 'Inquiring if Mono LMS supports direct xAPI/LRS webhooks for external LMS sync.'],
        ];
        foreach ($messages as $idx => [$mName, $mEmail, $mPhone, $mSub, $mMsg]) {
            ContactMessage::updateOrCreate(
                ['email' => $mEmail, 'subject' => $mSub],
                [
                    'name' => $mName,
                    'phone' => $mPhone,
                    'message' => $mMsg,
                    'is_read' => $idx > 0,
                ]
            );
        }

        // 7. Seed Leads (CRM)
        $leads = [
            ['Apex Innovations', 'hr@apexinnovate.com', '+1 800-555-0144', 'website', 'contacted', 'Interested in Enterprise Cohort package.'],
            ['DevBridge Studio', 'talent@devbridge.com', '+1 415-555-0182', 'referral', 'interested', 'Requested customized syllabus.'],
            ['Liam O\'Connor', 'liam.oc@outlook.com', null, 'google', 'new', 'Downloaded curriculum whitepaper.'],
            ['Sophia Lin', 'sophia@cloudsphere.io', '+1 650-555-0199', 'facebook', 'enrolled', 'Converted to annual membership.'],
        ];
        foreach ($leads as [$lName, $lEmail, $lPhone, $lSource, $lStatus, $lNotes]) {
            Lead::updateOrCreate(
                ['email' => $lEmail],
                [
                    'name' => $lName,
                    'phone' => $lPhone,
                    'source' => $lSource,
                    'status' => $lStatus,
                    'notes' => $lNotes,
                ]
            );
        }

        // 8. Seed Live Events
        if ($instructors->isNotEmpty()) {
            Event::updateOrCreate(
                ['title' => 'Live Q&A: Building Reactive Microservices with PHP 8.4'],
                [
                    'instructor_id' => $instructors->first()->id,
                    'start_time' => now()->addDays(3)->setHour(17)->setMinute(0),
                    'duration_minutes' => 90,
                    'meeting_link' => 'https://zoom.us/j/98421045091',
                    'capacity' => 100,
                    'is_published' => true,
                ]
            );
            Event::updateOrCreate(
                ['title' => 'Mastering UI/UX Systems in Figma: From Design to Token Code'],
                [
                    'instructor_id' => $instructors->skip(1)->first()?->id ?? $instructors->first()->id,
                    'start_time' => now()->addDays(7)->setHour(18)->setMinute(30),
                    'duration_minutes' => 60,
                    'meeting_link' => 'https://zoom.us/j/84920194821',
                    'capacity' => 75,
                    'is_published' => true,
                ]
            );
        }
    }
}
