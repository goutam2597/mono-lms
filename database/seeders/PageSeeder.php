<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'system_key' => 'about',
                'slug' => 'about',
                'title' => 'About Us',
                'template' => 'about',
                'status' => 'published',
                'is_system' => true,
                'meta_title' => 'About Us - Mono LMS',
                'meta_description' => 'Learn about Mono LMS, our global mission, expert instructors, and commitment to accessible world-class online learning.',
                'meta_keywords' => 'about mono lms, education platform, online courses, mission',
                'settings' => [
                    'eyebrow' => 'Our Purpose & Story',
                    'subtitle' => 'Empowering millions of learners and educators worldwide with industry-leading courses and hands-on skill development.',
                    'badge' => 'World-Class LMS Platform',
                    'mission_title' => 'Our Mission',
                    'mission' => 'To democratize professional education by delivering affordable, world-class learning experiences that unlock personal potential and drive meaningful career advancement across the globe.',
                    'vision_title' => 'Our Vision',
                    'vision' => 'To become the world’s most trusted learning ecosystem where knowledge meets real-world opportunity, enabling anyone, anywhere to transform their life through education.',
                    'story_title' => 'Built for the Future of Lifelong Learning',
                    'story_p1' => 'Mono LMS was founded with a singular conviction: that high-caliber, practical education should not be confined to elite institutions or geographical boundaries. We partner with top-tier practitioners, industry experts, and university researchers to curate comprehensive learning paths.',
                    'story_p2' => 'From software engineering and machine learning to digital business leadership and creative arts, our curriculum blends theory with interactive real-world projects, mentor assessments, and accredited certifications.',
                    'stats' => [
                        ['value' => '25K+', 'label' => 'Active Learners'],
                        ['value' => '1,200+', 'label' => 'Certified Courses'],
                        ['value' => '480+', 'label' => 'Industry Mentors'],
                        ['value' => '98.5%', 'label' => 'Satisfaction Rate'],
                    ],
                    'features' => [
                        [
                            'title' => 'Industry-Standard Curricula',
                            'description' => 'Every course is designed around real-world skills, case studies, and modern workflows demanded by leading employers.',
                            'icon' => 'academic',
                        ],
                        [
                            'title' => 'Global Expert Mentors',
                            'description' => 'Learn from verified industry veterans, published researchers, and senior engineers with proven track records.',
                            'icon' => 'users',
                        ],
                        [
                            'title' => 'Accredited Certificates',
                            'description' => 'Earn verified certificates with unique credential IDs recognized across corporate and academic sectors.',
                            'icon' => 'badge',
                        ],
                        [
                            'title' => 'Learn Anytime, Anywhere',
                            'description' => 'Fluid multi-device access with on-demand video streaming, progress tracking, and downloadable study kits.',
                            'icon' => 'globe',
                        ],
                    ],
                    'team' => [
                        [
                            'name' => 'Dr. Elena Rostova',
                            'role' => 'Chief Academic Officer',
                            'bio' => 'Former university dean and author with 18+ years leading progressive STEM education initiatives.',
                            'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
                        ],
                        [
                            'name' => 'Marcus Vance',
                            'role' => 'Head of Curriculum Engineering',
                            'bio' => 'Senior technologist previously leading engineering education frameworks at premier tech enterprises.',
                            'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                        ],
                        [
                            'name' => 'Sophia Chen',
                            'role' => 'VP of Learner Success',
                            'bio' => 'EdTech innovator focused on student retention, personalized mentorship, and career transition pathways.',
                            'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
                        ],
                        [
                            'name' => 'David K. Harris',
                            'role' => 'Director of Global Partnerships',
                            'bio' => 'Specializes in academic accreditation partnerships and corporate learning integrations.',
                            'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                        ],
                    ],
                ],
                'content' => null,
            ],
            [
                'system_key' => 'contact',
                'slug' => 'contact',
                'title' => 'Contact Us',
                'template' => 'contact',
                'status' => 'published',
                'is_system' => true,
                'meta_title' => 'Contact Us - Mono LMS',
                'meta_description' => 'Get in touch with the Mono LMS team for customer support, enterprise inquiries, instructor applications, or general questions.',
                'meta_keywords' => 'contact mono lms, support, helpdesk, office address',
                'settings' => [
                    'eyebrow' => 'Get in Touch',
                    'subtitle' => 'Have questions regarding our curriculum, enterprise subscriptions, or instructor onboarding? Our support team is here to help 24/7.',
                    'email' => 'support@mono-lms.com',
                    'phone' => '+1 (458) 877-7787',
                    'address' => '1442 Crosswind Drive, Madisonville, KY 42431, United States',
                    'office_hours' => 'Monday – Friday: 9:00 AM – 6:00 PM (EST)',
                    'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509374!2d144.9537353153166!3d-37.817323442021134!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d4c2b349649%3A0xb6899234e561db11!2sEnvato!5e0!3m2!1sen!2sau!4v1580975618585!5m2!1sen!2sau',
                    'form_enabled' => true,
                    'form_heading' => 'Send Us a Message',
                    'form_subtitle' => 'Fill out the form below and our dedicated support coordinators will reply within 24 business hours.',
                ],
                'content' => null,
            ],
            [
                'system_key' => 'privacy',
                'slug' => 'privacy',
                'title' => 'Privacy Policy',
                'template' => 'privacy',
                'status' => 'published',
                'is_system' => true,
                'meta_title' => 'Privacy Policy - Mono LMS',
                'meta_description' => 'Our complete privacy policy detailing how Mono LMS protects your personal information, cookie handling, and user privacy rights.',
                'meta_keywords' => 'privacy policy, gdpr, data protection, user rights',
                'settings' => [
                    'eyebrow' => 'Legal & Compliance',
                    'subtitle' => 'We are committed to absolute transparency, user data dignity, and safeguarding your personal information in accordance with global privacy laws.',
                    'effective_date' => 'November 1, 2026',
                    'dpo_email' => 'privacy@mono-lms.com',
                ],
                'content' => <<<'HTML'
<h3>1. Information We Collect</h3>
<p>We collect information to provide better educational services to our learners and instructors. This includes:</p>
<ul>
    <li><strong>Account Information:</strong> Full name, email address, password hash, phone number, and avatar upload provided during registration.</li>
    <li><strong>Learning Data:</strong> Course enrollments, lesson progress, quiz attempt scores, review feedback, and certificate issuances.</li>
    <li><strong>Payment Details:</strong> Transaction identifiers, billing postal codes, and payment gateway tokens. We never store raw credit card numbers directly on our servers.</li>
    <li><strong>Technical & Device Data:</strong> IP address, browser type, operating system, preferred language, and access timestamps collected through standard server logs.</li>
</ul>

<h3>2. How We Use Information</h3>
<p>We process collected data under legitimate educational and contractual purposes:</p>
<ul>
    <li>To administer accounts, authenticate logins, and facilitate self-service password resets.</li>
    <li>To stream course video lectures, deliver interactive quizzes, and accurately calculate curriculum completion percentages.</li>
    <li>To generate and cryptographically verify downloadable certificates of completion.</li>
    <li>To send essential transactional notifications, support ticket responses, and system security alerts.</li>
</ul>

<h3>3. Data Sharing & Third Parties</h3>
<p>Mono LMS does not sell, rent, or trade your personal information to third-party data brokers. We share data strictly with vetted infrastructure partners:</p>
<ul>
    <li>Payment gateways (e.g., Stripe, PayPal, Razorpay) strictly for completing tuition and order transactions.</li>
    <li>Cloud hosting and delivery network providers for reliable, low-latency video and asset distribution.</li>
    <li>Email dispatch services (SMTP) for deliverability of account verifications and receipts.</li>
</ul>

<h3>4. Cookies & Tracking Technologies</h3>
<p>We use essential cookies to maintain user authentication sessions, remember chosen dark/light interface themes, and preserve panel language preferences. You may configure your browser to decline non-essential cookies, though certain interactive features may be limited.</p>

<h3>5. Your Privacy Rights</h3>
<p>Depending on your jurisdiction (such as GDPR in the EU or CCPA in California), you possess the right to:</p>
<ul>
    <li>Request a machine-readable export of all personal data held about you.</li>
    <li>Request prompt rectification of incorrect or outdated profile details.</li>
    <li>Request permanent erasure of your account and associated learner data, subject to legal financial recordkeeping obligations.</li>
</ul>

<h3>6. Contact Our Data Protection Officer</h3>
<p>If you have any questions or concerns regarding our privacy standards or wish to exercise your data rights, please contact our Data Protection Office at <a href="mailto:privacy@mono-lms.com" class="text-[var(--theme-primary,#344c3d)] underline font-medium">privacy@mono-lms.com</a>.</p>
HTML
            ],
            [
                'system_key' => 'tnc',
                'slug' => 'terms',
                'title' => 'Terms & Conditions',
                'template' => 'tnc',
                'status' => 'published',
                'is_system' => true,
                'meta_title' => 'Terms & Conditions - Mono LMS',
                'meta_description' => 'Read the terms and conditions governing course enrollments, instructor agreements, and platform access on Mono LMS.',
                'meta_keywords' => 'terms and conditions, user agreement, terms of service, lms rules',
                'settings' => [
                    'eyebrow' => 'User Agreement',
                    'subtitle' => 'Please read these terms carefully before accessing courses, publishing content, or interacting with our community.',
                    'effective_date' => 'November 1, 2026',
                    'support_email' => 'legal@mono-lms.com',
                ],
                'content' => <<<'HTML'
<h3>1. Acceptance of Terms</h3>
<p>By registering an account, purchasing course access, or publishing curriculum on Mono LMS, you agree to be bound by these Terms and Conditions and our Privacy Policy. If you do not accept these terms, you must discontinue using the platform.</p>

<h3>2. User Accounts & Responsibilities</h3>
<ul>
    <li>You must provide accurate, complete registration credentials and maintain an active email address.</li>
    <li>You are solely responsible for all activities occurring under your credentials. Sharing credentials with third parties or concurrent multi-user viewing is prohibited.</li>
    <li>Instructors must undergo identity verification and submit accredited certificates before course publication privileges are granted.</li>
</ul>

<h3>3. Intellectual Property & Course Content</h3>
<ul>
    <li>All instructional videos, exercise source files, and course materials are the proprietary property of their respective creators and Mono LMS.</li>
    <li>Learners receive a limited, revocable, non-transferable personal license to view purchased course content for individual educational purposes.</li>
    <li>Republishing, reselling, screen recording for redistribution, or unauthorized distribution of course assets will result in immediate termination and legal action.</li>
</ul>

<h3>4. Purchases, Subscriptions & Refunds</h3>
<ul>
    <li>Tuition prices are clearly listed in the course catalog and are inclusive of applicable taxes unless stated otherwise.</li>
    <li>Eligible individual course purchases may be requested for refund within 14 days of purchase provided less than 25% of lessons have been consumed and no certificate was issued.</li>
    <li>Recurring membership plans renew automatically until cancelled via the account settings panel prior to the billing renewal date.</li>
</ul>

<h3>5. Instructor Code of Conduct</h3>
<ul>
    <li>Instructors warrant that their uploaded video lectures, notes, and quiz questions do not infringe any third-party copyrights or trademarks.</li>
    <li>Instructors must respond to student course inquiries with professional courtesy and maintain up-to-date curricula.</li>
</ul>

<h3>6. Termination & Disclaimers</h3>
<p>Mono LMS reserves the right to suspend or terminate accounts that violate community safety standards, practice unauthorized commercial solicitation, or engage in malicious hacking attempts. Services are provided on an "as is" and "as available" basis.</p>
HTML
            ],
            [
                'system_key' => 'instructors',
                'slug' => 'instructors',
                'title' => 'Our Expert Instructors',
                'template' => 'instructors',
                'status' => 'published',
                'is_system' => true,
                'meta_title' => 'Expert Instructors Directory - Mono LMS',
                'meta_description' => 'Explore our directory of verified expert instructors, university professors, and industry leaders teaching on Mono LMS.',
                'meta_keywords' => 'instructors directory, verified tutors, expert teachers, learn from mentors',
                'settings' => [
                    'eyebrow' => 'Global Faculty Directory',
                    'subtitle' => 'Learn from verified industry veterans, published authors, and experienced mentors passionate about your mastery.',
                    'badge' => 'Verified Academic Faculty',
                    'cta_banner_enabled' => true,
                    'cta_heading' => 'Are you passionate about teaching? Share your mastery.',
                    'cta_subtitle' => 'Join our faculty of world-class instructors. Create interactive courses, mentor ambitious students, and earn recurring revenue.',
                    'cta_button_text' => 'Become an Instructor',
                    'cta_button_url' => '/instructor/register',
                ],
                'content' => null,
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(
                ['system_key' => $data['system_key']],
                $data
            );
        }
    }
}
