<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class HomePageContent
{
    public const SETTING_KEY = 'home_content';

    public const CACHE_KEY = 'settings.home_content_parsed';

    /**
     * Canonical defaults for all 13 homepage sections.
     *
     * @return array<string, array<string, mixed>>
     */
    public function defaults(): array
    {
        return [
            'hero' => [
                'enabled' => true,
                'badge' => 'Over 3,500 Online Courses',
                'title_line1' => 'Expand Your Skill,',
                'title_highlight' => 'Knowledge',
                'title_line2' => 'and Learn',
                'subtitle' => 'Explore our catalog of top-rated courses taught by industry leaders and university faculty worldwide.',
                'rating_label' => '4.8 out of 5',
                'students_count_text' => '350,000+ Students',
                'avatar_image' => '',
                'badge_students_count' => '25K+',
                'badge_students_label' => 'Enrolled',
                'badge_courses_count' => '1,200+',
                'badge_courses_label' => 'Courses',
                'badge_tutors_count' => '480+',
                'badge_tutors_label' => 'Tutors',
            ],
            'stats' => [
                'enabled' => true,
                'use_dynamic_counts' => true,
                'stat1_value' => '25K+',
                'stat1_label' => 'Enrolled Students',
                'stat2_value' => '1,200+',
                'stat2_label' => 'Active Courses',
                'stat3_value' => '480+',
                'stat3_label' => 'Expert Instructors',
                'stat4_value' => '98.5%',
                'stat4_label' => 'Satisfaction Rate',
            ],
            'categories' => [
                'enabled' => true,
                'eyebrow' => 'Top Categories',
                'heading' => 'Master the Skills to Drive Your Career',
                'subtitle' => 'Explore our comprehensive catalog organized across key industry domains.',
                'limit' => 8,
            ],
            'featured_courses' => [
                'enabled' => true,
                'eyebrow' => "What's New",
                'heading' => 'Featured Courses',
                'subtitle' => 'Hand-picked high-demand masterclasses taught by top tier experts',
                'button_text' => 'View all Courses',
                'limit' => 8,
            ],
            'banner_transform' => [
                'enabled' => true,
                'heading' => 'Transform Access To Education',
                'description' => 'Create an account to receive our newsletter, course recommendations and promotions.',
                'button_text' => 'View all Courses',
                'button_url' => '/courses',
                'background_image' => '',
            ],
            'goals' => [
                'enabled' => true,
                'heading' => 'Achieve Your Goals with MonoLMS',
                'stat1_value' => '253,085',
                'stat1_label' => 'Students Enrolled all over World',
                'stat2_value' => '1,205',
                'stat2_label' => 'Total Courses on our Platform',
                'stat3_value' => '56',
                'stat3_label' => 'Various Countries of Students',
            ],
            'accelerate' => [
                'enabled' => true,
                'heading' => 'Accelerate Cloud Success',
                'description' => '96% of eLearning for Business customers see improved results within six months. Whether you’re a team of 10 or 10,000, faster cloud fluency starts here.',
                'showcase1_title' => 'Upskill your team with modern labs',
                'showcase1_desc' => 'Hands-on projects and interactive sandboxes designed for team productivity.',
                'showcase1_image1' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80',
                'showcase1_image2' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=700&auto=format&fit=crop&q=80',
                'showcase1_metric1_val' => '1,500+',
                'showcase1_metric1_lbl' => 'Certifications earned',
                'showcase1_metric2_val' => '95%',
                'showcase1_metric2_lbl' => 'Completion rate',
                'showcase2_title' => 'Stay ahead of industry shifts',
                'showcase2_desc' => 'Fresh curricula updated monthly by real practitioners and cloud architects.',
                'showcase2_image1' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop&q=80',
                'showcase2_image2' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=700&auto=format&fit=crop&q=80',
                'showcase3_title' => 'Enterprise learning governance',
                'showcase3_desc' => 'Track progress, assign seats, and export deep analytics seamlessly.',
                'showcase3_image1' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80',
                'showcase3_image2' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=700&auto=format&fit=crop&q=80',
            ],
            'trending_courses' => [
                'enabled' => true,
                'eyebrow' => 'Trending Now',
                'heading' => 'Most Trending Courses',
                'subtitle' => 'The most popular courses chosen by thousands of learners this week.',
                'limit' => 4,
            ],
            'instructors' => [
                'enabled' => true,
                'eyebrow' => 'Expert Guidance',
                'heading' => 'Courses Taught by Real Instructors',
                'subtitle' => 'Learn from leading practitioners, founders, and subject-matter experts worldwide.',
                'button_text' => 'Meet All Instructors',
                'limit' => 4,
            ],
            'banner_instructor' => [
                'enabled' => true,
                'heading' => 'Become An Instructor',
                'description' => 'Top instructors from around the world teach millions of students on MonoLMS.',
                'button_text' => 'Join Our Community',
                'button_url' => '/instructor/login',
                'background_image' => '',
            ],
            'partners' => [
                'enabled' => true,
                'heading' => '500+ Leading Universities And Companies',
                'button_text' => 'View All',
                'button_url' => '/courses',
                'partners_list' => [
                    ['name' => 'Jira Software', 'logo' => ''],
                    ['name' => 'Calendly', 'logo' => ''],
                    ['name' => 'LiveChat', 'logo' => ''],
                    ['name' => 'Slack', 'logo' => ''],
                    ['name' => 'Google Cloud', 'logo' => ''],
                    ['name' => 'Atlassian', 'logo' => ''],
                ],
            ],
            'testimonials' => [
                'enabled' => true,
                'heading' => 'Trusted By Many',
                'subtitle' => 'We are very happy because we have happy Learners',
                'button_text' => 'Join Our Community',
                'button_url' => '/register',
                'quote' => '“I often felt like the mentor’s answers were too detailed, which made it hard for me to keep up. Sometimes, a simpler explanation would have helped me understand things faster. But overall, I’ve become more organized, confident, and focused thanks to my life coach.”',
                'author_name' => 'Martin Harn',
                'author_role' => 'Docker Development',
                'author_rating' => '4.5',
                'author_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop',
            ],
            'blog' => [
                'enabled' => true,
                'heading' => 'Latest News & Events',
                'button_text' => 'View All',
                'button_url' => '/blog',
                'limit' => 4,
            ],
        ];
    }

    /**
     * Return all sections with stored settings merged over defaults.
     *
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        try {
            return Cache::remember(self::CACHE_KEY, now()->addHour(), function () {
                $raw = Setting::get(self::SETTING_KEY);
                $stored = [];
                if (! empty($raw)) {
                    $decoded = json_decode($raw, true);
                    if (is_array($decoded)) {
                        $stored = $decoded;
                    }
                }

                return $this->mergeWithDefaults($stored);
            });
        } catch (\Throwable) {
            return $this->defaults();
        }
    }

    /**
     * Return a specific section's merged configuration.
     *
     * @return array<string, mixed>
     */
    public function section(string $name): array
    {
        $all = $this->all();

        return $all[$name] ?? ($this->defaults()[$name] ?? []);
    }

    /**
     * Save updated homepage sections into settings.
     *
     * @param  array<string, mixed>  $input
     */
    public function save(array $input): void
    {
        $defaults = $this->defaults();
        $sections = $input['sections'] ?? $input;
        if (! is_array($sections)) {
            $sections = [];
        }

        $clean = [];
        foreach ($defaults as $secKey => $secDefaults) {
            $submitted = $sections[$secKey] ?? [];
            if (! is_array($submitted)) {
                $submitted = [];
            }

            $cleanSection = [];
            foreach ($secDefaults as $fieldKey => $defaultVal) {
                if (is_bool($defaultVal)) {
                    if (array_key_exists($fieldKey, $submitted)) {
                        $val = $submitted[$fieldKey];
                        $cleanSection[$fieldKey] = ($val === true || $val === 1 || $val === '1' || $val === 'on');
                    } else {
                        // Unchecked checkbox in standard submission
                        $cleanSection[$fieldKey] = false;
                    }
                } elseif (is_int($defaultVal)) {
                    $cleanSection[$fieldKey] = isset($submitted[$fieldKey]) && is_numeric($submitted[$fieldKey])
                        ? (int) $submitted[$fieldKey]
                        : $defaultVal;
                } elseif (is_array($defaultVal)) {
                    $cleanSection[$fieldKey] = isset($submitted[$fieldKey]) && is_array($submitted[$fieldKey])
                        ? $submitted[$fieldKey]
                        : $defaultVal;
                } else {
                    $cleanSection[$fieldKey] = array_key_exists($fieldKey, $submitted)
                        ? (string) $submitted[$fieldKey]
                        : (string) $defaultVal;
                }
            }

            $clean[$secKey] = $cleanSection;
        }

        Setting::set(self::SETTING_KEY, json_encode($clean, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Deep merge stored section values over canonical defaults.
     *
     * @param  array<string, mixed>  $stored
     * @return array<string, array<string, mixed>>
     */
    protected function mergeWithDefaults(array $stored): array
    {
        $defaults = $this->defaults();
        $merged = [];

        foreach ($defaults as $secKey => $secDefaults) {
            $userSection = $stored[$secKey] ?? [];
            if (! is_array($userSection)) {
                $userSection = [];
            }

            $clean = [];
            foreach ($secDefaults as $fieldKey => $defaultVal) {
                if (! array_key_exists($fieldKey, $userSection)) {
                    $clean[$fieldKey] = $defaultVal;
                } elseif (is_bool($defaultVal)) {
                    $clean[$fieldKey] = (bool) $userSection[$fieldKey];
                } elseif (is_int($defaultVal)) {
                    $clean[$fieldKey] = (int) $userSection[$fieldKey];
                } elseif (is_array($defaultVal)) {
                    $clean[$fieldKey] = is_array($userSection[$fieldKey]) ? $userSection[$fieldKey] : $defaultVal;
                } else {
                    $clean[$fieldKey] = (string) $userSection[$fieldKey];
                }
            }

            $merged[$secKey] = $clean;
        }

        return $merged;
    }
}
