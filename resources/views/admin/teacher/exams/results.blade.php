<x-teacher-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Hasil Ujian: {{ $exam->title }}</h1>
            <a href="{{ route('admin.exams') }}" class="btn btn-light btn-icon-split shadow-sm rounded-xl transition-all">
                <span class="icon text-gray-600"><i class="fas fa-arrow-left"></i></span>
                <span class="text">Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom-light">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-poll-h mr-2"></i>Daftar Nilai Siswa</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                    <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                        <tr>
                            <th class="border-0 px-4 py-3">Nama Siswa</th>
                            <th class="border-0 px-4 py-3">NISN</th>
                            <th class="border-0 px-4 py-3">Skor Ujian</th>
                            <th class="border-0 px-4 py-3">Waktu Pengerjaan</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-800 font-weight-500">
                        @forelse($results as $res)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-weight-600 text-slate-800">{{ $res->user->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-slate-600">{{ $res->user->nisn ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge badge-pill bg-indigo-50 text-indigo font-weight-bold px-3 py-2" style="font-size: 1rem;">
                                        {{ $res->score }} / 100
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-slate-700">{{ \Carbon\Carbon::parse($res->created_at)->translatedFormat('d M Y, H:i') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <div class="mb-3"><i class="fas fa-poll fa-3x opacity-20"></i></div>
                                    <p class="mb-0 font-weight-500">Belum ada siswa yang mengikuti ujian ini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-teacher-layout>
