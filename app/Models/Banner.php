<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function scopeLive($query)
    {
        return $query
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query
                    ->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->orderBy('sort_order')
            ->orderByDesc('id');
    }

    public function getLinkUrlAttribute(): ?string
    {
        $destination = trim((string) $this->button_url);

        if ($destination === '') {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $destination)) {
            return $destination;
        }

        return url('/'.ltrim($destination, '/'));
    }

    public function getLinkIsExternalAttribute(): bool
    {
        return (bool) preg_match(
            '/^https?:\/\//i',
            trim((string) $this->button_url)
        );
    }
}