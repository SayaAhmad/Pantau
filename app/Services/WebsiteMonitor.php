<?php

namespace App\Services;

use App\Models\MonitorCheck;
use App\Models\Website;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\StreamHandler;
use Throwable;

class WebsiteMonitor
{
    public function check(Website $website): MonitorCheck
    {
        $started = microtime(true);

        try {
            $response = (new Client([
                'handler' => new StreamHandler(),
                'allow_redirects' => false,
                'http_errors' => false,
                'timeout' => 12,
                'headers' => ['User-Agent' => 'PantauWeb/1.0 (+website availability monitor)'],
            ]))->get($website->url);
            $httpStatus = $response->getStatusCode();
            $status = $httpStatus >= 200 && $httpStatus < 400 ? 'up' : 'down';
            $message = $status === 'up' ? 'Website merespons dengan baik.' : 'Website membalas dengan HTTP '.$httpStatus.'.';
        } catch (Throwable $exception) {
            $status = 'down';
            $httpStatus = null;
            $message = 'Tidak dapat terhubung: '.$exception->getMessage();
        }

        $check = $website->checks()->create([
            'status' => $status,
            'http_status' => $httpStatus,
            'response_time_ms' => (int) round((microtime(true) - $started) * 1000),
            'message' => mb_substr($message, 0, 255),
            'checked_at' => now(),
        ]);

        $website->forceFill(['next_check_at' => now()->addMinutes((int) $website->check_interval)])->save();

        return $check;
    }
}
