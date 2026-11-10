@php
    // $prefix: unique field-group prefix; $lesson: optional existing lesson (edit mode)
    $currentSource = ($lesson ?? null)?->isUploadedVideo() ? 'upload' : (($lesson ?? null)?->video_source === \App\Models\Lesson::SOURCE_UPLOAD ? 'upload' : 'youtube');
@endphp

<div class="grid grid-cols-1 gap-3">
    <div class="flex flex-wrap items-center gap-4">
        <label class="flex items-center gap-2 text-xs font-semibold text-[#191919] cursor-pointer">
            <input type="radio" name="video_source" value="youtube" @checked($currentSource === 'youtube') class="control mt-0"> YouTube URL
        </label>
        <label class="flex items-center gap-2 text-xs font-semibold text-[#191919] cursor-pointer">
            <input type="radio" name="video_source" value="upload" @checked($currentSource === 'upload') class="control mt-0"> Upload video file
        </label>
    </div>

    <div data-video-youtube class="space-y-1.5 {{ $currentSource === 'upload' ? 'hidden' : '' }}">
        <input type="text" name="video_url" value="{{ old('video_url', ($lesson ?? null)?->video_url) }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full border border-[#e7e7e7] rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#738a6e]">
        @error('video_url')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
    </div>

    <div data-video-upload class="space-y-1.5 {{ $currentSource === 'upload' ? '' : 'hidden' }}">
        <input type="file" name="video" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-matroska" class="w-full text-sm text-gray-500 file:mr-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-[#344c3d] file:text-white file:text-xs file:font-semibold file:cursor-pointer">
        @error('video')<p class="text-[11px] text-red-500">{{ $message }}</p>@enderror
        @if (($lesson ?? null)?->isUploadedVideo())
            <input type="hidden" name="keep_video_path" value="{{ $lesson->video_path }}">
            <p class="text-[11px] text-emerald-700">Current file kept — choose a new file above to replace it.</p>
        @endif
        <p class="text-[11px] text-gray-400">MP4 / WebM / OGG / MOV / MKV · up to 500 MB (server php.ini limits apply).</p>
    </div>
</div>
