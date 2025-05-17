<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\Widget;

class SystemStatusWidget extends Widget
{
    protected int|string|array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.system-status';

    public function getStatusData(): array
    {
        return [
            'Scheduled Posts' => Post::scheduled()->count(),
            'Scheduled Pages' => Page::scheduled()->count(),
            'Pending Users' => User::where('status', User::STATUS_PENDING)->count(),
            'Storage Usage' => $this->getStorageUsage('auto'), // Bisa 'auto', 'MB', 'GB', dst.
        ];
    }

    /**
     * Menghitung penggunaan storage dengan dukungan satuan fleksibel.
     *
     * @param string $unit Satuan: B, KB, MB, GB, TB, atau 'auto'
     * @return string
     */
    protected function getStorageUsage(string $unit = 'GB'): string
    {
        $storagePath = storage_path();

        $totalSpace = @disk_total_space($storagePath);
        $freeSpace = @disk_free_space($storagePath);

        if ($totalSpace === false || $freeSpace === false) {
            return 'Storage usage unavailable';
        }

        $usedSpace = $totalSpace - $freeSpace;

        $units = [
            'B'  => 1,
            'KB' => 1024,
            'MB' => 1024 ** 2,
            'GB' => 1024 ** 3,
            'TB' => 1024 ** 4,
        ];

        // Otomatis pilih satuan jika 'auto'
        if ($unit === 'auto') {
            foreach (array_reverse($units) as $u => $div) {
                if ($totalSpace >= $div) {
                    $unit = $u;
                    break;
                }
            }
        }

        $unit = strtoupper($unit);

        $divider = $units[$unit] ?? $units['GB']; // fallback to GB jika tidak valid

        $used = number_format($usedSpace / $divider, 2);
        $total = number_format($totalSpace / $divider, 2);

        return "{$used} {$unit} / {$total} {$unit}";
    }
}
