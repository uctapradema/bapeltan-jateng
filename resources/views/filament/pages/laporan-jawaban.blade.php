@if(!$jawaban)
    <p class="text-gray-500">Belum ada jawaban.</p>
@elseif($tahapan && $tahapan->tipe === 'harian')
    @php $riwayat = $jawaban['riwayat'] ?? []; @endphp
    @if(empty($riwayat))
        <p class="text-gray-500">Belum ada riwayat harian.</p>
    @else
        <div class="space-y-4">
            @php krsort($riwayat); @endphp
            @foreach($riwayat as $date => $entry)
                @php
                    $waktu = $entry['waktu'] ?? '-';
                    $answers = $entry['jawaban'] ?? [];
                    $questions = \App\Models\PelatihanTahapanQuestion::whereIn('id', array_keys($answers))->get()->keyBy('id');
                @endphp
                <div class="border rounded-lg p-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-sm">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span>
                        <span class="text-xs text-gray-400">{{ $waktu }}</span>
                    </div>
                    <div class="space-y-2">
                        @foreach($answers as $qId => $value)
                            @php
                                $question = $questions[$qId] ?? null;
                                $qLabel = $question->pertanyaan ?? "Pertanyaan #{$qId}";
                                $tipe = $question->tipe ?? 'text';
                                if ($tipe === 'checkbox' && is_array($value)) {
                                    $display = implode(', ', $value);
                                } elseif ($tipe === 'rating') {
                                    $display = str_repeat('★', $value) . str_repeat('☆', 5 - $value) . " ({$value}/5)";
                                } else {
                                    $display = $value;
                                }
                            @endphp
                            <div class="flex gap-2">
                                <span class="text-xs font-medium text-gray-500 min-w-[120px]">{{ $qLabel }}:</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $display }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@else
    @php
        $questions = \App\Models\PelatihanTahapanQuestion::whereIn('id', array_keys($jawaban))->get()->keyBy('id');
    @endphp
    <div class="space-y-2">
        @foreach($jawaban as $qId => $value)
            @php
                $question = $questions[$qId] ?? null;
                $qLabel = $question->pertanyaan ?? "Pertanyaan #{$qId}";
                $tipe = $question->tipe ?? 'text';
                if ($tipe === 'checkbox' && is_array($value)) {
                    $display = implode(', ', $value);
                } elseif ($tipe === 'rating') {
                    $display = str_repeat('★', $value) . str_repeat('☆', 5 - $value) . " ({$value}/5)";
                } else {
                    $display = $value;
                }
            @endphp
            <div class="flex gap-2">
                <span class="text-xs font-medium text-gray-500 min-w-[120px]">{{ $qLabel }}:</span>
                <span class="text-sm text-gray-900 dark:text-white">{{ $display }}</span>
            </div>
        @endforeach
    </div>
@endif
