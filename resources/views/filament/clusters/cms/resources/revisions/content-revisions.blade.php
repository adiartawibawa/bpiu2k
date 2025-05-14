<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            Detail Revisi Konten
        </h2>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ $record->created_at->format('d M Y H:i') }}
        </div>
    </div>

    {{-- Basic Info --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe Konten</h3>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                {{ class_basename($record->subject_type) }}
            </p>
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Konten</h3>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                {{ $record->subject_id }}
            </p>
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Diubah Oleh</h3>
            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                {{ $record->causer?->name ?? 'System' }}
            </p>
        </div>
    </div>

    {{-- Changes --}}
    <div class="mt-6">
        <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Perubahan</h3>

        @if ($record->event === 'updated')
            <div class="overflow-hidden border border-gray-200 rounded-lg dark:border-gray-700">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                                Field</th>
                            <th
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                                Nilai Lama</th>
                            <th
                                class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                                Nilai Baru</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @foreach ($record->changes['attributes'] as $field => $newValue)
                            <tr>
                                <td
                                    class="px-4 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $field }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                    {{ $record->changes['old'][$field] ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                    {{ $newValue }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800">
                <p class="text-gray-600 dark:text-gray-300">
                    {{ ucfirst($record->event) }} event - Tidak ada perubahan detail yang tersedia
                </p>
            </div>
        @endif
    </div>
</div>
