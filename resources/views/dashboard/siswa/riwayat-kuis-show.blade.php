@extends('dashboard.siswa.layout')

@section('content')
    <div class="section-card">
        <span class="tag">Detail Kuis</span>
        <h2 class="section-title">{{ $hasil->kuis->judul ?? 'Kuis' }}</h2>
        <p class="section-desc">Mata Pelajaran: {{ $hasil->kuis->materi->judul ?? '-' }}</p>
        <div style="margin-top:0.75rem;">
            <div class="section-title" style="font-size:1.8rem; color:#16A34A;">{{ $hasil->skor }}%</div>
            <p class="section-desc">Benar {{ $hasil->total_benar }} dari {{ $hasil->total_pertanyaan }} soal</p>
            <p class="section-desc" style="font-size:0.85rem;">
                Selesai: {{ $hasil->selesai_at ? \Carbon\Carbon::parse($hasil->selesai_at)->format('d M Y, H:i') : '-' }}
            </p>
        </div>
    </div>

    <div class="section-card" style="margin-top:1.5rem;">
        <span class="tag">Jawaban</span>
        <h3 class="section-title">Rincian Jawaban</h3>
        <div class="section-grid" style="margin-top:1rem;">
            @foreach($hasil->jawaban as $index => $jawaban)
                @php
                    $p = $jawaban->pertanyaan;
                    $status = $jawaban->status_koreksi ?? 'approved';
                    $statusLabel = $status === 'pending' ? 'Menunggu Koreksi' : ($status === 'rejected' ? 'Ditolak' : 'Disetujui');
                    $statusColor = $status === 'pending' ? '#B45309' : ($status === 'rejected' ? '#B91C1C' : '#16A34A');
                @endphp
                <article class="section-card">
                    <span class="tag">Soal {{ $index + 1 }}</span>
                    <h3 class="section-title" style="margin-bottom:0.5rem;">{{ $p?->pertanyaan }}</h3>
                    <p class="section-desc" style="margin-bottom:0.5rem;">
                        Tipe: {{ $p?->tipe ?? '-' }}
                    </p>

                    @if($p && in_array($p->tipe, ['essay','speaking']))
                        <p class="section-desc"><strong>Jawaban kamu:</strong></p>
                        <p class="section-desc" style="background:#F9FAFB; padding:0.75rem; border-radius:12px; border:1px solid var(--color-gray);">
                            {{ $jawaban->jawaban_teks ?? '-' }}
                        </p>
                        <p class="section-desc" style="margin-top:0.5rem;">
                            Skor otomatis: {{ $jawaban->skor_auto ?? 0 }}%
                        </p>
                        <p class="section-desc" style="margin-top:0.25rem; color:{{ $statusColor }};">
                            Status koreksi: {{ $statusLabel }}
                        </p>
                    @else
                        <p class="section-desc">
                            Jawaban kamu: {{ $jawaban->opsi?->label ?? '-' }}
                        </p>
                        <p class="section-desc">
                            Status: {{ $jawaban->benar ? 'Benar' : 'Salah' }}
                        </p>
                    @endif
                </article>
            @endforeach
        </div>
    </div>

    <div style="margin-top:1.5rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
        <a href="{{ route('dashboard.siswa.riwayat') }}" class="btn btn-secondary">Kembali ke Riwayat</a>
        @if($hasil->kuis->materi_id)
            <a href="{{ route('dashboard.siswa.materi.show', $hasil->kuis->materi_id) }}" class="btn btn-secondary">Buka Mata Pelajaran</a>
        @endif
    </div>
@endsection
