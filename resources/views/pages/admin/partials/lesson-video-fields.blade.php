@php
    // $prefix: unique field-group prefix; $lesson: optional existing lesson (edit mode)
    $currentSource = ($lesson ?? null)?->isUploadedVideo() ? 'upload' : (($lesson ?? null)?->video_source === \App\Models\Lesson::SOURCE_UPLOAD ? 'upload' : 'youtube');
@endphp

<div class="space-y-3 pt-2">
    <div>
        <label class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-400 block mb-2">Video Lesson Source</label>
        <div class="flex flex-wrap items-center gap-5">
            <label class="flex items-center gap-2 text-xs font-semibold text-neutral-800 dark:text-neutral-200 cursor-pointer select-none">
                <input type="radio" name="video_source" value="youtube" @checked($currentSource === 'youtube') class="control mt-0">
                <span>YouTube URL</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-semibold text-neutral-800 dark:text-neutral-200 cursor-pointer select-none">
                <input type="radio" name="video_source" value="upload" @checked($currentSource === 'upload') class="control mt-0">
                <span>Upload Video File</span>
            </label>
        </div>
    </div>

    <div data-video-youtube class="space-y-1.5 {{ $currentSource === 'upload' ? 'hidden' : '' }}">
        <input type="text" name="video_url" value="{{ old('video_url', ($lesson ?? null)?->video_url) }}" placeholder="https://www.youtube.com/watch?v=..." class="h-10 w-full border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 rounded-lg px-3.5 text-sm focus:outline-none focus:border-neutral-900">
        @error('video_url')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
    </div>

    <div data-video-upload class="space-y-1.5 {{ $currentSource === 'upload' ? '' : 'hidden' }}">
        <input type="file" name="video" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-matroska" class="w-full text-sm text-neutral-600 dark:text-neutral-300 file:mr-3 file:px-4 file:py-2 file:border file:border-neutral-300 dark:file:border-neutral-700 file:bg-white dark:file:bg-neutral-800 file:rounded-lg file:text-xs file:font-semibold file:cursor-pointer">
        @error('video')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
        @if (($lesson ?? null)?->isUploadedVideo())
            <input type="hidden" name="keep_video_path" value="{{ $lesson->video_path }}">
            <p class="text-[11px] text-emerald-700 dark:text-emerald-400 font-medium">✓ Current video file kept — choose a new file above to replace it.</p>
        @endif
        <p class="text-[11px] text-neutral-400">MP4, WebM, OGG, MOV, MKV · up to 500 MB</p>
    </div>

    <!-- Free Preview / Unlocked Lesson Toggle -->
    <div class="pt-3 border-t border-neutral-200 dark:border-neutral-800">
        <label class="flex items-start gap-2.5 cursor-pointer select-none">
            <input type="checkbox" name="is_free" value="1" @checked(old('is_free', ($lesson ?? null)?->is_free)) class="control mt-0.5">
            <div>
                <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 12.75v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Free Preview Lesson (Unlocked)
                </span>
                <span class="text-[11px] text-neutral-500 dark:text-neutral-400 block mt-0.5">Students can watch this lesson free before purchasing or enrolling to get interested in the course.</span>
            </div>
        </label>
    </div>
</div>
