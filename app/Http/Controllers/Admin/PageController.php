<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');
        $query = Page::query();

        if ($filter === 'published') {
            $query->where('status', 'published');
        } elseif ($filter === 'draft') {
            $query->where('status', 'draft');
        } elseif ($filter === 'system') {
            $query->where('is_system', true);
        } elseif ($filter === 'custom') {
            $query->where('is_system', false);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('system_key', 'like', "%{$search}%");
            });
        }

        $pages = $query->orderByDesc('is_system')->orderBy('title')->paginate(15)->withQueryString();

        $counts = [
            'all' => Page::count(),
            'published' => Page::where('status', 'published')->count(),
            'draft' => Page::where('status', 'draft')->count(),
            'system' => Page::where('is_system', true)->count(),
            'custom' => Page::where('is_system', false)->count(),
        ];

        return view('pages.admin.pages.index', [
            'pages' => $pages,
            'counts' => $counts,
            'currentFilter' => $filter,
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.pages.form', [
            'page' => new Page([
                'status' => 'published',
                'template' => 'standard',
                'is_system' => false,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePage($request);
        $settings = $this->extractSettings($request, $validated['template']);

        $content = $validated['content'] ?? null;
        if (empty($content) && ! empty($settings['blocks'])) {
            $content = $this->compileBlocksToHtml($settings['blocks']);
        }

        $page = Page::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'template' => $validated['template'],
            'content' => $content,
            'status' => $validated['status'],
            'settings' => $settings,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'og_image' => $validated['og_image'] ?? null,
            'is_system' => false,
        ]);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', "Page '{$page->title}' created successfully with permalink /{$page->slug}.");
    }

    public function edit(Page $page): View
    {
        return view('pages.admin.pages.form', [
            'page' => $page,
        ]);
    }

    public function editBySlug(string $slug): View|RedirectResponse
    {
        $page = Page::where('system_key', $slug)
            ->orWhere('slug', $slug)
            ->firstOrFail();

        return view('pages.admin.pages.form', [
            'page' => $page,
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $this->validatePage($request, $page->id);
        $settings = $this->extractSettings($request, $validated['template'], $page->settings ?? []);

        $content = $validated['content'] ?? null;
        if (empty($content) && ! empty($settings['blocks'])) {
            $content = $this->compileBlocksToHtml($settings['blocks']);
        }

        $page->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'template' => $validated['template'],
            'content' => $content,
            'status' => $validated['status'],
            'settings' => $settings,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'og_image' => $validated['og_image'] ?? null,
        ]);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', "Page '{$page->title}' updated successfully. Permalink: /{$page->slug}");
    }

    public function destroy(Page $page): RedirectResponse
    {
        if ($page->is_system) {
            return back()->with('error', 'System pages cannot be deleted. You can unpublish or customize them instead.');
        }

        $title = $page->title;
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', "Page '{$title}' was deleted successfully.");
    }

    public function duplicate(Page $page): RedirectResponse
    {
        $baseSlug = $page->slug.'-copy';
        $slug = $baseSlug;
        $counter = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $newPage = Page::create([
            'title' => 'Copy of '.$page->title,
            'slug' => $slug,
            'template' => $page->template,
            'content' => $page->content,
            'settings' => $page->settings,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'meta_keywords' => $page->meta_keywords,
            'og_image' => $page->og_image,
            'status' => 'draft',
            'is_system' => false,
        ]);

        return redirect()->route('admin.pages.edit', $newPage)
            ->with('success', "Page duplicated as draft with permalink /{$newPage->slug}.");
    }

    protected function validatePage(Request $request, ?int $pageId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->ignore($pageId),
                'regex:/^[a-zA-Z0-9\-_]+$/',
            ],
            'template' => ['required', 'string', 'in:about,contact,privacy,tnc,instructors,standard'],
            'status' => ['required', 'string', 'in:published,draft'],
            'content' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'string', 'max:500'],
        ]);
    }

    /**
     * Parse structured settings for the page based on template.
     */
    protected function extractSettings(Request $request, string $template, array $existing = []): array
    {
        $settings = $existing;
        $input = $request->input('settings', []);

        if (! is_array($input)) {
            return $settings;
        }

        // Shared / common
        if (isset($input['subtitle'])) {
            $settings['subtitle'] = trim((string) $input['subtitle']);
        }
        if (isset($input['eyebrow'])) {
            $settings['eyebrow'] = trim((string) $input['eyebrow']);
        }

        // Template specific
        if ($template === 'about') {
            $settings['mission_title'] = $input['mission_title'] ?? ($existing['mission_title'] ?? 'Our Mission');
            $settings['mission'] = $input['mission'] ?? ($existing['mission'] ?? '');
            $settings['vision_title'] = $input['vision_title'] ?? ($existing['vision_title'] ?? 'Our Vision');
            $settings['vision'] = $input['vision'] ?? ($existing['vision'] ?? '');
            $settings['story_title'] = $input['story_title'] ?? ($existing['story_title'] ?? '');
            $settings['story_p1'] = $input['story_p1'] ?? ($existing['story_p1'] ?? '');
            $settings['story_p2'] = $input['story_p2'] ?? ($existing['story_p2'] ?? '');

            // Stats
            if (isset($input['stats']) && is_array($input['stats'])) {
                $settings['stats'] = array_values(array_filter($input['stats'], fn ($s) => ! empty($s['value']) || ! empty($s['label'])));
            }

            // Features / Highlights
            if (isset($input['features']) && is_array($input['features'])) {
                $settings['features'] = array_values(array_filter($input['features'], fn ($f) => ! empty($f['title'])));
            }

            // Team
            if (isset($input['team']) && is_array($input['team'])) {
                $settings['team'] = array_values(array_filter($input['team'], fn ($t) => ! empty($t['name'])));
            }
        } elseif ($template === 'contact') {
            $settings['email'] = $input['email'] ?? ($existing['email'] ?? '');
            $settings['phone'] = $input['phone'] ?? ($existing['phone'] ?? '');
            $settings['address'] = $input['address'] ?? ($existing['address'] ?? '');
            $settings['office_hours'] = $input['office_hours'] ?? ($existing['office_hours'] ?? '');
            $settings['map_embed_url'] = $input['map_embed_url'] ?? ($existing['map_embed_url'] ?? '');
            $settings['form_enabled'] = isset($input['form_enabled']) ? (bool) $input['form_enabled'] : true;
            $settings['form_heading'] = $input['form_heading'] ?? ($existing['form_heading'] ?? 'Send Us a Message');
            $settings['form_subtitle'] = $input['form_subtitle'] ?? ($existing['form_subtitle'] ?? '');
        } elseif ($template === 'privacy' || $template === 'tnc') {
            $settings['effective_date'] = $input['effective_date'] ?? ($existing['effective_date'] ?? date('F 1, Y'));
            $settings['dpo_email'] = $input['dpo_email'] ?? ($existing['dpo_email'] ?? 'privacy@mono-lms.com');
            $settings['support_email'] = $input['support_email'] ?? ($existing['support_email'] ?? 'legal@mono-lms.com');
        } elseif ($template === 'instructors') {
            $settings['badge'] = $input['badge'] ?? ($existing['badge'] ?? 'Global Faculty Directory');
            $settings['cta_banner_enabled'] = isset($input['cta_banner_enabled']) ? (bool) $input['cta_banner_enabled'] : true;
            $settings['cta_heading'] = $input['cta_heading'] ?? ($existing['cta_heading'] ?? 'Are you passionate about teaching? Share your mastery.');
            $settings['cta_subtitle'] = $input['cta_subtitle'] ?? ($existing['cta_subtitle'] ?? '');
            $settings['cta_button_text'] = $input['cta_button_text'] ?? ($existing['cta_button_text'] ?? 'Become an Instructor');
            $settings['cta_button_url'] = $input['cta_button_url'] ?? ($existing['cta_button_url'] ?? '/instructor/register');
        }

        // Visual Page Builder Blocks
        if ($request->has('settings_blocks_json') && filled($request->input('settings_blocks_json'))) {
            $decodedBlocks = json_decode((string) $request->input('settings_blocks_json'), true);
            if (is_array($decodedBlocks)) {
                $settings['blocks'] = $decodedBlocks;
            }
        } elseif (isset($input['blocks']) && is_array($input['blocks'])) {
            $settings['blocks'] = $input['blocks'];
        }

        return $settings;
    }

    /**
     * Compile structured visual builder blocks into clean semantic HTML.
     *
     * @param  array<int, array<string, mixed>>  $blocks
     */
    protected function compileBlocksToHtml(array $blocks): string
    {
        $html = [];

        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';
            $data = $block['data'] ?? [];

            switch ($type) {
                case 'hero':
                    $title = e($data['title'] ?? 'Headline');
                    $subtitle = e($data['subtitle'] ?? '');
                    $btnLabel = e($data['btn_primary_label'] ?? '');
                    $btnUrl = e($data['btn_primary_url'] ?? '#');
                    $html[] = "<div class=\"block-hero my-8 p-8 sm:p-12 bg-slate-900 text-white rounded-2xl text-center\">
                        <h1 class=\"text-3xl sm:text-5xl font-extrabold mb-4\">{$title}</h1>
                        ".($subtitle ? "<p class=\"text-slate-300 text-base sm:text-lg max-w-2xl mx-auto mb-6\">{$subtitle}</p>" : '').'
                        '.($btnLabel ? "<a href=\"{$btnUrl}\" class=\"inline-block px-6 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm\">{$btnLabel}</a>" : '').'
                    </div>';
                    break;

                case 'features_grid':
                    $title = e($data['title'] ?? 'Our Key Features');
                    $itemsHtml = '';
                    foreach ($data['items'] ?? [] as $item) {
                        $itTitle = e($item['title'] ?? '');
                        $itDesc = e($item['description'] ?? '');
                        $itemsHtml .= "<div class=\"p-6 rounded-xl border border-slate-200 bg-white shadow-xs\">
                            <h3 class=\"text-lg font-bold text-slate-900 mb-2\">{$itTitle}</h3>
                            <p class=\"text-sm text-slate-600 leading-relaxed\">{$itDesc}</p>
                        </div>";
                    }
                    $html[] = "<div class=\"block-features my-12\">
                        <h2 class=\"text-2xl sm:text-3xl font-bold text-slate-900 text-center mb-8\">{$title}</h2>
                        <div class=\"grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6\">{$itemsHtml}</div>
                    </div>";
                    break;

                case 'split_content':
                    $title = e($data['title'] ?? '');
                    $desc = nl2br(e($data['description'] ?? ''));
                    $img = e($data['image'] ?? '');
                    $pointsHtml = '';
                    foreach ($data['points'] ?? [] as $pt) {
                        $ptStr = e($pt);
                        $pointsHtml .= "<li class=\"flex items-center gap-2 text-sm text-slate-700\"><span>✓</span> <span>{$ptStr}</span></li>";
                    }
                    $html[] = "<div class=\"block-split my-12 grid grid-cols-1 md:grid-cols-2 gap-8 items-center\">
                        <div>
                            <h2 class=\"text-2xl font-bold text-slate-900 mb-4\">{$title}</h2>
                            <div class=\"text-sm text-slate-600 leading-relaxed mb-4\">{$desc}</div>
                            ".($pointsHtml ? "<ul class=\"space-y-2 mb-6\">{$pointsHtml}</ul>" : '').'
                        </div>
                        '.($img ? "<div><img src=\"{$img}\" alt=\"{$title}\" class=\"rounded-2xl w-full object-cover shadow-md\"></div>" : '').'
                    </div>';
                    break;

                case 'faq':
                    $title = e($data['title'] ?? 'Frequently Asked Questions');
                    $faqItems = '';
                    foreach ($data['items'] ?? [] as $faq) {
                        $q = e($faq['question'] ?? '');
                        $a = nl2br(e($faq['answer'] ?? ''));
                        $faqItems .= "<details class=\"group border border-slate-200 rounded-xl p-4 bg-slate-50/50 mb-3\">
                            <summary class=\"font-bold text-sm text-slate-900 cursor-pointer list-none flex justify-between items-center\">
                                <span>{$q}</span>
                                <span class=\"text-slate-400 group-open:rotate-180 transition-transform\">&darr;</span>
                            </summary>
                            <div class=\"text-xs text-slate-600 mt-3 pt-3 border-t border-slate-200 leading-relaxed\">{$a}</div>
                        </details>";
                    }
                    $html[] = "<div class=\"block-faq my-12\">
                        <h2 class=\"text-2xl font-bold text-slate-900 text-center mb-6\">{$title}</h2>
                        <div class=\"max-w-3xl mx-auto\">{$faqItems}</div>
                    </div>";
                    break;

                case 'cta_banner':
                    $title = e($data['title'] ?? 'Ready to Get Started?');
                    $subtitle = e($data['subtitle'] ?? '');
                    $btnLabel = e($data['btn_label'] ?? 'Join Now');
                    $btnUrl = e($data['btn_url'] ?? '#');
                    $html[] = "<div class=\"block-cta my-12 p-8 sm:p-10 rounded-2xl bg-[#344c3d] text-white text-center\">
                        <h2 class=\"text-2xl sm:text-3xl font-extrabold mb-3\">{$title}</h2>
                        ".($subtitle ? "<p class=\"text-slate-200 text-sm max-w-xl mx-auto mb-6\">{$subtitle}</p>" : '')."
                        <a href=\"{$btnUrl}\" class=\"inline-block px-6 py-3 rounded-lg bg-white text-[#344c3d] font-bold text-xs uppercase tracking-wider shadow-md hover:bg-slate-100\">{$btnLabel}</a>
                    </div>";
                    break;

                case 'stats':
                    $statsHtml = '';
                    foreach ($data['items'] ?? [] as $stat) {
                        $v = e($stat['value'] ?? '');
                        $l = e($stat['label'] ?? '');
                        $statsHtml .= "<div class=\"text-center p-4 bg-slate-50 rounded-xl border border-slate-100\">
                            <div class=\"text-3xl font-extrabold text-slate-900\">{$v}</div>
                            <div class=\"text-xs text-slate-500 uppercase tracking-wider mt-1 font-semibold\">{$l}</div>
                        </div>";
                    }
                    $html[] = "<div class=\"block-stats my-8 grid grid-cols-2 md:grid-cols-4 gap-4\">{$statsHtml}</div>";
                    break;

                case 'testimonial':
                    $quote = e($data['quote'] ?? '');
                    $author = e($data['author_name'] ?? '');
                    $role = e($data['author_role'] ?? '');
                    $html[] = "<div class=\"block-testimonial my-10 p-8 rounded-2xl border border-slate-200 bg-slate-50/60 text-center max-w-2xl mx-auto\">
                        <p class=\"text-base sm:text-lg italic text-slate-800 mb-4\">“{$quote}”</p>
                        <div class=\"font-bold text-sm text-slate-900\">{$author}</div>
                        ".($role ? "<div class=\"text-xs text-slate-500\">{$role}</div>" : '').'
                    </div>';
                    break;

                case 'rich_text':
                    $body = $data['body'] ?? '';
                    $html[] = "<div class=\"block-rich-text my-8 prose prose-slate max-w-none\">{$body}</div>";
                    break;
            }
        }

        return implode("\n\n", $html);
    }
}
