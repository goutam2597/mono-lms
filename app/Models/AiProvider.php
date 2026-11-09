<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiProvider extends Model
{
    public const TYPE_OPENAI = 'openai';

    public const TYPE_ANTHROPIC = 'anthropic';

    public const TYPES = [self::TYPE_OPENAI, self::TYPE_ANTHROPIC];

    public const DEFAULT_URLS = [
        self::TYPE_OPENAI => 'https://api.openai.com/v1',
        self::TYPE_ANTHROPIC => 'https://api.anthropic.com/v1',
    ];

    protected $fillable = [
        'name',
        'type',
        'base_url',
        'api_key',
        'model',
        'models',
        'selected_models',
        'models_fetched_at',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'api_key' => 'encrypted',
        'models' => 'array',
        'selected_models' => 'array',
        'models_fetched_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $hidden = ['api_key'];

    /** Masked key for display: ••••1234 */
    public function maskedKey(): string
    {
        $key = (string) $this->api_key;

        return $key === '' ? '' : '••••'.substr($key, -4);
    }

    /** Models the admin chose to use (falls back to the legacy single model). */
    public function availableModels(): array
    {
        $selected = $this->selected_models ?? [];

        if (empty($selected) && $this->model) {
            $selected = [$this->model];
        }

        return array_values($selected);
    }

    /**
     * Resolve a stored "{provider_id}|{model}" selection.
     */
    public static function resolveConfigured(string $settingKey): ?static
    {
        $raw = (string) Setting::get($settingKey);
        if ($raw === '') {
            return null;
        }

        $id = $raw;
        $model = null;
        if (str_contains($raw, '|')) {
            [$id, $model] = explode('|', $raw, 2);
        }

        $provider = static::find((int) $id);
        if (! $provider || ! $provider->is_active) {
            return null;
        }

        $available = $provider->availableModels();
        if ($model !== null && $model !== '') {
            if ($available !== [] && ! in_array($model, $available, true)) {
                return null;
            }
            $provider->model = $model;
        } elseif (! $provider->model) {
            $provider->model = $available[0] ?? null;
        }

        return $provider->model ? $provider : null;
    }
}
