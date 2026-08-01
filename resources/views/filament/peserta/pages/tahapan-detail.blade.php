<x-filament::page>
    @if($tahapan)
        <div class="mb-4">
            <a href="{{ route('filament.peserta.pages.kegiatan-tahapan-page', ['kegiatanId' => $tahapan['kegiatan_id']]) }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 mb-2">
                <x-heroicon-m-arrow-left class="w-4 h-4" />
                Kembali
            </a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $tahapan['nama'] }}</h1>
            <p class="text-sm text-gray-500">{{ $tahapan['kegiatan_nama'] }}</p>
            @if($tahapan['deskripsi'])
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $tahapan['deskripsi'] }}</p>
            @endif
            @if($isHarian)
                <x-filament::badge size="sm" color="warning" class="mt-2">Harian — Isi Setiap Hari</x-filament::badge>
            @endif
        </div>

        @if($tahapan['link'])
            <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    <span class="font-medium">Link:</span>
                    <a href="{{ $tahapan['link'] }}" target="_blank" class="underline hover:no-underline">{{ $tahapan['link'] }}</a>
                </p>
            </div>
        @endif

        @if($isCompleted && !$isHarian)
            <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                <p class="text-sm text-green-700 dark:text-green-300 font-medium">
                    <x-heroicon-m-check-circle class="w-4 h-4 inline" />
                    Tahapan ini sudah diselesaikan.
                </p>
            </div>
        @endif

        {{-- Form Isi Hari Ini --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4 mb-4">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                @if($isHarian)
                    Isi Hari Ini — {{ \Carbon\Carbon::now()->format('d M Y') }}
                @else
                    Formulir
                @endif
            </h2>

            @if(count($questions) > 0)
                <form wire:submit="{{ $isHarian ? 'simpanJawaban' : 'selesaikanTahapan' }}">
                    <div class="space-y-4">
                        @foreach($questions as $idx => $q)
                            @php $qId = (string) $q['id']; @endphp
                            <div class="rounded-lg border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50 p-3">
                                <div class="flex items-start gap-2 mb-3">
                                    <span class="text-xs font-bold text-gray-400 mt-0.5">{{ $idx + 1 }}.</span>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $q['pertanyaan'] }}</p>
                                        @if($q['wajib'])
                                            <span class="text-xs text-red-500">*wajib</span>
                                        @endif
                                    </div>
                                </div>

                                @if($q['tipe'] === 'pilihan_ganda')
                                    <div class="ml-6 space-y-2">
                                        @foreach($q['opsi'] ?? [] as $op)
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio"
                                                       name="jawaban_{{ $qId }}"
                                                       value="{{ $op['label'] ?? $op }}"
                                                       wire:model.live="jawaban.{{ $qId }}"
                                                       class="text-primary-600 focus:ring-primary-500"
                                                       @if($isCompleted && !$isHarian) disabled @endif>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $op['label'] ?? $op }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                @elseif($q['tipe'] === 'checkbox')
                                    <div class="ml-6 space-y-2">
                                        @foreach($q['opsi'] ?? [] as $op)
                                            @php $opVal = $op['label'] ?? $op; @endphp
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox"
                                                       value="{{ $opVal }}"
                                                       wire:model.live="jawaban.{{ $qId }}"
                                                       class="text-primary-600 focus:ring-primary-500 rounded"
                                                       @if($isCompleted && !$isHarian) disabled @endif>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $opVal }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                @elseif($q['tipe'] === 'text')
                                    <div class="ml-6">
                                        <input type="text"
                                               wire:model.live="jawaban.{{ $qId }}"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm"
                                               placeholder="Jawaban..."
                                               @if($isCompleted && !$isHarian) disabled @endif>
                                    </div>

                                @elseif($q['tipe'] === 'textarea')
                                    <div class="ml-6">
                                        <textarea wire:model.live="jawaban.{{ $qId }}"
                                                  rows="3"
                                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm"
                                                  placeholder="Tuliskan jawaban..."
                                                  @if($isCompleted && !$isHarian) disabled @endif></textarea>
                                    </div>

                                @elseif($q['tipe'] === 'rating')
                                    <div class="ml-6 flex gap-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button"
                                                    wire:click="setRating('{{ $qId }}', {{ $i }})"
                                                    class="w-10 h-10 rounded-lg border text-sm font-bold transition
                                                           {{ ($jawaban[$qId] ?? 0) >= $i
                                                              ? 'bg-primary-500 text-white border-primary-500'
                                                              : 'bg-white dark:bg-gray-800 text-gray-500 border-gray-300 dark:border-gray-600 hover:border-primary-400' }}"
                                                    @if($isCompleted && !$isHarian) disabled @endif>
                                                {{ $i }}
                                            </button>
                                        @endfor
                                    </div>

                                @elseif($q['tipe'] === 'konfirmasi')
                                    <div class="ml-6 flex gap-3">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio"
                                                   name="jawaban_{{ $qId }}"
                                                   value="ya"
                                                   wire:model.live="jawaban.{{ $qId }}"
                                                   class="text-primary-600 focus:ring-primary-500"
                                                   @if($isCompleted && !$isHarian) disabled @endif>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Ya</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio"
                                                   name="jawaban_{{ $qId }}"
                                                   value="tidak"
                                                   wire:model.live="jawaban.{{ $qId }}"
                                                   class="text-primary-600 focus:ring-primary-500"
                                                   @if($isCompleted && !$isHarian) disabled @endif>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Tidak</span>
                                        </label>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if(!$isCompleted || $isHarian)
                        <div class="mt-4">
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                                {{ $isHarian ? 'Simpan Hari Ini' : 'Simpan & Selesaikan' }}
                            </button>
                        </div>
                    @endif
                </form>
            @else
                <div class="py-8 text-center text-gray-500">
                    <x-heroicon-o-question-mark-circle class="mx-auto mb-2 h-10 w-10 text-gray-300" />
                    <p class="font-medium">Tidak ada pertanyaan</p>
                    <p class="text-sm">Tahapan ini belum memiliki pertanyaan.</p>
                    @if(!$isCompleted)
                        <button wire:click="selesaikanTahapan({{ $this->tahapanId }})"
                                class="mt-4 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                            Selesaikan Tahapan
                        </button>
                    @endif
                </div>
            @endif
        </div>

        {{-- Riwayat Harian --}}
        @if($isHarian && count($riwayatHarian) > 0)
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Riwayat</h2>
                <div class="space-y-2">
                    @foreach(collect($riwayatHarian)->sortByDesc('keys') as $date => $entry)
                        <div class="flex items-center justify-between py-2 px-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <div class="flex items-center gap-2">
                                @if($date === $todayDate)
                                    <x-filament::badge size="xs" color="success">Hari Ini</x-filament::badge>
                                @endif
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                                </span>
                            </div>
                            <span class="text-xs text-gray-400">{{ $entry['waktu'] ?? '-' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</x-filament::page>
