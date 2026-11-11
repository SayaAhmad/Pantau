<?php

namespace App\Console\Commands;

use App\Models\Website;
use App\Services\WebsiteMonitor;
use Illuminate\Console\Command;

class CheckDueWebsites extends Command
{
    protected $signature = 'monitors:check';

    protected $description = 'Check websites whose monitoring interval has elapsed';

    public function handle(WebsiteMonitor $monitor): int
    {
        $websites = Website::query()
            ->where('is_active', true)
            ->where(fn ($query) => $query->whereNull('next_check_at')->orWhere('next_check_at', '<=', now()))
            ->get();

        foreach ($websites as $website) {
            $check = $monitor->check($website);
            $this->line($website->name.': '.strtoupper($check->status).' ('.$check->response_time_ms.' ms)');
        }

        $this->info($websites->count().' website(s) checked.');

        return self::SUCCESS;
    }
}
