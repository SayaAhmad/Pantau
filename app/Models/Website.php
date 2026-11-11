<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Website extends Model
{
    protected $fillable = ['user_id', 'name', 'url', 'check_interval', 'is_active', 'next_check_at'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'check_interval' => 'integer', 'next_check_at' => 'datetime'];
    }

    public function checks(): HasMany
    {
        return $this->hasMany(MonitorCheck::class)->latest('checked_at');
    }

    public function latestCheck(): HasMany
    {
        return $this->checks()->limit(1);
    }
}
