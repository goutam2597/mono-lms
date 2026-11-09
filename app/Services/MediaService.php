<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Media;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class MediaService
{
    /** Validation ceiling for an image upload, in kilobytes. */
    public const IMAGE_MAX_KB = 5120;

    /** SVG uploads are sanitized on store: they can carry active content. */
    public const SVG_MIME = 'image/svg+xml';

    /**
     * Does this upload carry SVG content? Checked by extension and by the
     * real (finfo) mime type, so a renamed HTML file is never treated as SVG.
     */
    public static function isSvg(UploadedFile $file): bool
    {
        return strtolower($file->getClientOriginalExtension()) === 'svg'
            || $file->getMimeType() === self::SVG_MIME;
    }

    /**
     * Store an image upload, sanitizing SVGs on the way in.
     */
    public static function storeImageUpload(UploadedFile $file, string $directory, string $disk = 'public'): string
    {
        if (! self::isSvg($file)) {
            return (new self)->storeStream($file, $directory, $disk);
        }

        $sanitized = self::sanitizeSvg($file->getContent() ?: '');
        $path = $directory.'/'.Str::random(40).'.svg';
        Storage::disk($disk)->put($path, $sanitized);

        return $path;
    }

    /**
     * Strip active content from an uploaded SVG.
     */
    public static function sanitizeSvg(string $contents): string
    {
        if (stripos($contents, '<!DOCTYPE') !== false || stripos($contents, '<!ENTITY') !== false) {
            throw new RuntimeException('The SVG file contains a DOCTYPE declaration, which is not allowed.');
        }

        $doc = new DOMDocument;
        libxml_use_internal_errors(true);
        $loaded = $doc->loadXML($contents, LIBXML_NONET);
        libxml_clear_errors();
        if (! $loaded || $doc->documentElement === null) {
            throw new RuntimeException('The SVG file is not well-formed XML.');
        }

        $xpath = new DOMXPath($doc);

        $banned = ['script', 'foreignobject', 'iframe', 'embed', 'object'];
        $nodes = iterator_to_array($xpath->query('//*') ?: []);
        foreach ($nodes as $node) {
            if (in_array(strtolower((string) $node->localName), $banned, true)) {
                $node->parentNode?->removeChild($node);
            }
        }

        $attrs = iterator_to_array($xpath->query('//@*') ?: []);
        foreach ($attrs as $attr) {
            $name = strtolower($attr->nodeName);
            $value = strtolower(trim((string) $attr->nodeValue));
            $owner = $attr->ownerElement;

            if ($owner === null) {
                continue;
            }

            if (str_starts_with($name, 'on')) {
                $owner->removeAttribute($attr->nodeName);

                continue;
            }

            if (in_array($name, ['href', 'xlink:href'], true)
                && (str_contains($value, 'javascript:') || str_starts_with($value, 'data:text/html'))) {
                $owner->removeAttribute($attr->nodeName);
            }
        }

        $out = $doc->saveXML();

        return $out === false ? throw new RuntimeException('The SVG file could not be serialized.') : $out;
    }

    /**
     * Read pixel dimensions from an SVG's width/height attributes or viewBox.
     *
     * @return array{0: ?int, 1: ?int}
     */
    public static function svgDimensions(string $xml): array
    {
        if (! preg_match('/<svg\b[^>]*>/i', $xml, $tag)) {
            return [null, null];
        }

        $tag = $tag[0];
        $width = null;
        $height = null;

        if (preg_match('/\bwidth\s*=\s*"?([\d.]+)/i', $tag, $m)) {
            $width = (float) $m[1];
        }
        if (preg_match('/\bheight\s*=\s*"?([\d.]+)/i', $tag, $m)) {
            $height = (float) $m[1];
        }

        if ((! $width || ! $height) && preg_match('/viewBox\s*=\s*["\']\s*[-\d.]+[\s,]+[-\d.]+[\s,]+([\d.]+)[\s,]+([\d.]+)/i', $tag, $m)) {
            $width = (float) $m[1];
            $height = (float) $m[2];
        }

        return [
            $width ? (int) round($width) : null,
            $height ? (int) round($height) : null,
        ];
    }

    /**
     * The size an image upload may actually reach, clamped by php.ini.
     */
    public static function imageMaxKb(): int
    {
        $ini = self::iniKb((string) ini_get('upload_max_filesize'));

        return max(1, min(self::IMAGE_MAX_KB, $ini));
    }

    /** Human label for the effective limit, e.g. "5 MB". */
    public static function imageMaxLabel(): string
    {
        $kb = self::imageMaxKb();

        return $kb >= 1024 ? round($kb / 1024, 1).' MB' : $kb.' KB';
    }

    private static function iniKb(string $value): int
    {
        $value = trim($value);

        if ($value === '' || (int) $value === 0) {
            return PHP_INT_MAX;
        }

        $unit = strtolower(substr($value, -1));
        $number = (int) $value;

        return match ($unit) {
            'g' => $number * 1024 * 1024,
            'm' => $number * 1024,
            default => $number,
        };
    }

    public function storeUploadedFile(UploadedFile $file, ?Admin $admin = null, ?Instructor $instructor = null): Media
    {
        $isSvg = self::isSvg($file);
        $width = null;
        $height = null;

        if ($isSvg) {
            $sanitized = self::sanitizeSvg($file->getContent() ?: '');
            $path = 'media/'.date('Y/m').'/'.Str::random(40).'.svg';
            Storage::disk('public')->put($path, $sanitized);
            [$width, $height] = self::svgDimensions($sanitized);
        } else {
            $path = $this->storeStream($file, 'media/'.date('Y/m'), 'public');

            if (str_starts_with($file->getMimeType() ?? '', 'image/')) {
                $source = $file->getPathname();
                $dims = is_string($source) && $source !== '' ? @getimagesize($source) : false;
                if ($dims !== false) {
                    [$width, $height] = $dims;
                }
            }
        }

        return Media::create([
            'disk' => 'public',
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'mime' => $isSvg ? self::SVG_MIME : $file->getMimeType(),
            'size' => $file->getSize() ?? 0,
            'width' => $width,
            'height' => $height,
            'admin_id' => $admin?->id,
            'instructor_id' => $instructor?->id,
        ]);
    }

    /**
     * Write an upload to disk by streaming its raw temp path.
     * Avoids Windows "Path must not be empty" realpath issues.
     */
    private function storeStream(UploadedFile $file, string $directory, string $disk): string
    {
        $extension = strtolower(
            $file->getClientOriginalExtension() ?: ($file->extension() ?: 'bin')
        );

        $path = $directory.'/'.Str::random(40).'.'.$extension;
        $source = $file->getPathname();

        if (! is_string($source) || $source === '' || ! is_readable($source)) {
            throw new RuntimeException(
                'The uploaded file has no readable temporary copy. '
                .'Check upload_tmp_dir permissions for the web server.'
            );
        }

        $stream = fopen($source, 'rb');

        try {
            Storage::disk($disk)->put($path, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        return $path;
    }
}
