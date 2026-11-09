<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Throwable;

class MediaController extends Controller
{
    public function __construct(protected MediaService $mediaService) {}

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $perPage = (int) $request->query('per_page', 30);
        $perPage = in_array($perPage, [24, 30, 48, 60], true) ? $perPage : 30;

        $media = Media::query()
            ->latest()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('alt', 'like', "%{$q}%");
                });
            })
            ->paginate($perPage)
            ->appends($request->query());

        return view('pages.admin.media', compact('media', 'q', 'perPage'));
    }

    public function json(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));
        $perPage = (int) $request->query('per_page', 30);
        $perPage = in_array($perPage, [24, 30, 48, 60], true) ? $perPage : 30;

        $media = Media::query()
            ->latest()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('alt', 'like', "%{$q}%");
                });
            })
            ->paginate($perPage)
            ->appends($request->query());

        return response()->json([
            'data' => $media->getCollection()->map(fn (Media $m) => [
                'id' => $m->id,
                'name' => $m->name,
                'url' => $m->url(),
                'path' => $m->path,
                'size' => (int) $m->size,
                'width' => $m->width,
                'height' => $m->height,
            ])->values(),
            'current_page' => $media->currentPage(),
            'last_page' => $media->lastPage(),
            'total' => $media->total(),
            'per_page' => $media->perPage(),
        ]);
    }

    public function quickUpload(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'files' => ['required', 'array', 'max:20'],
                'files.*' => ['required', 'image:allow_svg', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.MediaService::imageMaxKb()],
            ]);

            $admin = auth('admin')->user();
            $out = [];

            foreach ($request->file('files') as $file) {
                $m = $this->mediaService->storeUploadedFile($file, $admin);

                $out[] = [
                    'id' => $m->id,
                    'name' => $m->name,
                    'url' => $m->url(),
                    'path' => $m->path,
                    'size' => (int) $m->size,
                    'width' => $m->width,
                    'height' => $m->height,
                ];
            }

            return response()->json(['data' => $out]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->validator->errors()->first(),
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json(['message' => $this->uploadErrorMessage($e)], 500);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'files' => ['required', 'array', 'max:20'],
            'files.*' => ['required', 'image:allow_svg', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.MediaService::imageMaxKb()],
        ]);

        $admin = auth('admin')->user();

        try {
            foreach ($request->file('files') as $file) {
                $this->mediaService->storeUploadedFile($file, $admin);
            }
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors(['files' => $this->uploadErrorMessage($e)]);
        }

        return redirect()->route('admin.media.index')->with('success', __('admin.media.uploaded'));
    }

    public function destroy(Request $request, Media $media): RedirectResponse
    {
        $media->delete();

        return redirect()->route('admin.media.index')->with('success', __('admin.media.deleted'));
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:media,id'],
        ]);

        $ids = $request->input('ids', []);

        foreach ($ids as $id) {
            $m = Media::find($id);
            if ($m) {
                $m->delete();
            }
        }

        return redirect()->route('admin.media.index')->with('success', __('admin.media.bulk_deleted'));
    }

    private function uploadErrorMessage(Throwable $e): string
    {
        for ($cursor = $e; $cursor !== null; $cursor = $cursor->getPrevious()) {
            $message = $cursor->getMessage();

            if ($cursor instanceof FileNotFoundException
                || $cursor instanceof \RuntimeException
                || str_contains($message, 'Path must not be empty')
                || str_contains($message, 'temporary copy')
                || str_contains($message, 'does not exist')) {
                return __('admin.media.upload_temp_unavailable');
            }
        }

        return __('admin.media.upload_failed_generic');
    }
}
