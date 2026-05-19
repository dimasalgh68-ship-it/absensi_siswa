<x-teacher-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Jadwal Mengajar</h1>
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary btn-icon-split shadow-sm hover-translate-y transition-all">
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                <span class="text">Tambah Jadwal</span>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-left: 4px solid #1cc88a !important;">
            <i class="fas fa-check-circle mr-2 text-success"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom-light">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-week mr-2"></i>Jadwal Pembelajaran Mingguan</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                    <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                        <tr>
                            <th class="border-0 px-4 py-3">Hari</th>
                            <th class="border-0 px-4 py-3">Mata Pelajaran</th>
                            <th class="border-0 px-4 py-3">Kelas</th>
                            <th class="border-0 px-4 py-3">Waktu</th>
                            <th class="border-0 px-4 py-3">Ruangan</th>
                            <th class="border-0 px-4 py-3">Guru</th>
                            <th class="border-0 px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-800 font-weight-500">
                        @forelse($schedules as $schedule)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="badge badge-pill bg-indigo-50 text-indigo font-weight-bold px-3 py-2">{{ $schedule->day }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-weight-600 text-slate-800">{{ $schedule->subject->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge badge-pill bg-blue-50 text-blue font-weight-bold px-3 py-2">{{ $schedule->education->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-slate-700">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-weight-600 text-slate-700">{{ $schedule->room ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-weight-600 text-slate-700">{{ $schedule->teacher->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-light btn-sm rounded-xl px-3 mr-1 hover-translate-y transition-all">
                                        <i class="fas fa-edit text-warning mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light btn-sm rounded-xl px-3 hover-translate-y transition-all" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                            <i class="fas fa-trash text-danger mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="mb-3"><i class="fas fa-calendar-times fa-3x opacity-20"></i></div>
                                    <p class="mb-0 font-weight-500">Belum ada jadwal mengajar</p>
                                    <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary btn-sm mt-3 rounded-xl px-4">Tambah Jadwal Pertama</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-teacher-layout>
