<?php

namespace App\Http\Controllers;

use App\Models\MonitorCheck;
use App\Models\Website;
use App\Services\WebsiteMonitor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    public function index(): View
    {
        $websites = Website::query()->where('user_id', auth()->id())->with(['checks' => fn ($query) => $query->limit(8)])->latest()->get();
        $checks = MonitorCheck::query()->where('checked_at', '>=', now()->subDay())
            ->whereHas('website', fn ($query) => $query->where('user_id', auth()->id()))->get();
        $total = $websites->count();
        $up = $websites->filter(fn ($website) => $website->checks->first()?->status === 'up')->count();
        $avg = $checks->whereNotNull('response_time_ms')->avg('response_time_ms');

        return view('dashboard', compact('websites', 'total', 'up', 'avg'));
    }

    public function store(Request $request, WebsiteMonitor $monitor): RedirectResponse
    {
        $data = $this->validated($request);
        $website = Website::create($data + ['is_active' => true, 'user_id' => $request->user()->id]);
        $monitor->check($website);

        return back()->with('success', 'Website berhasil ditambahkan dan pemeriksaan pertama sudah dilakukan.');
    }

    public function update(Request $request, Website $website): RedirectResponse
    {
        abort_unless($website->user_id === $request->user()->id, 404);
        $website->update($this->validated($request));

        return back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }

    public function destroy(Request $request, Website $website): RedirectResponse
    {
        abort_unless($website->user_id === $request->user()->id, 404);
        $website->delete();

        return back()->with('success', 'Website dan riwayat pemeriksaannya telah dihapus.');
    }

    public function checkNow(Request $request, Website $website, WebsiteMonitor $monitor): RedirectResponse
    {
        abort_unless($website->user_id === $request->user()->id, 404);
        $check = $monitor->check($website);

        return back()->with('success', $check->status === 'up' ? 'Pemeriksaan selesai: website aktif.' : 'Pemeriksaan selesai: website tidak merespons.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'url' => ['required', 'url:http,https', 'max:255'],
            'check_interval' => ['required', 'integer', 'in:1,5,10,15,30,60'],
        ]);
    }
}
