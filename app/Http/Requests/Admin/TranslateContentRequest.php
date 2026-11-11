<?php

namespace App\Http\Requests\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class TranslateContentRequest extends FormRequest
{
    private const FIELDS = [
        'course' => ['title', 'subtitle', 'description'],
        'category' => ['name'],
        'taxonomy' => ['name'],
        'blog' => ['title', 'excerpt', 'content'],
        'post' => ['title', 'excerpt', 'content'],
        'page' => ['*'],
    ];

    public function authorize(): bool
    {
        if (auth('admin')->check()) {
            return true;
        }

        if (auth('instructor')->check()) {
            $context = (string) $this->input('context');

            return in_array($context, ['course'], true);
        }

        return false;
    }

    public function rules(): array
    {
        $codes = Language::active()->pluck('code')->all();

        return [
            'context' => ['required', 'string', Rule::in(array_keys(self::FIELDS))],
            'source' => ['nullable', 'string', Rule::in($codes)],
            'targets' => ['nullable', 'array', 'max:20'],
            'targets.*' => ['string', 'distinct', Rule::in($codes)],
            'fields' => ['required', 'array', 'min:1', 'max:20'],
            'fields.*' => ['array', 'max:250'],
            'fields.*.*' => ['nullable', 'string', 'max:20000'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            $fields = $this->input('fields', []);
            $activeCodes = Language::active()->pluck('code')->all();
            $context = (string) $this->input('context');
            $allowed = self::FIELDS[$context] ?? [];
            $totalLength = 0;
            $fieldCount = 0;

            foreach ($fields as $code => $values) {
                if (! in_array($code, $activeCodes, true)) {
                    $validator->errors()->add("fields.{$code}", __('admin.languages.translate_invalid'));

                    continue;
                }

                if (! is_array($values)) {
                    continue;
                }

                foreach ($values as $key => $value) {
                    $fieldCount++;
                    $totalLength += is_string($value) ? mb_strlen($value) : 0;
                    $baseKey = (string) preg_replace('/__(?:seg|retry)\d+(?:__retry\d+)?$/', '', (string) $key);
                    if (! in_array('*', $allowed, true) && ! in_array($baseKey, $allowed, true)) {
                        $validator->errors()->add("fields.{$code}.{$key}", __('admin.languages.translate_invalid'));
                    }
                }
            }

            if ($fieldCount > 500 || $totalLength > 100000) {
                $validator->errors()->add('fields', __('admin.languages.translate_too_large'));
            }
        }];
    }
}
