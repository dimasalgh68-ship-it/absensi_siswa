<x-dynamic-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Detail Tugas</h1>
            <a href="{{ route('admin.tasks') }}" class="btn btn-light btn-icon-split shadow-sm rounded-xl transition-all">
                <span class="icon text-gray-600"><i class="fas fa-arrow-left"></i></span>
                <span class="text">Kembali</span>
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

    <div class="row">
        <!-- Task Details -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle mr-2"></i>Informasi Tugas</h6>
                </div>
                <div class="card-body p-4">
                    <h4 class="font-weight-bold text-slate-800 mb-3">{{ $task->title }}</h4>
                    <p class="text-slate-600 mb-4" style="line-height: 1.6;">{!! nl2br(e($task->description)) !!}</p>

                    @if($task->image_path)
                        <div class="mb-4">
                            <label class="font-weight-600 text-slate-700 d-block">Lampiran Gambar</label>
                            <img src="{{ Storage::url($task->image_path) }}" alt="Task Image" class="img-fluid rounded-xl shadow-sm" style="max-height: 250px; object-fit: cover; width: 100%;">
                        </div>
                    @endif

                    @if($task->link)
                        <div class="mb-4">
                            <label class="font-weight-600 text-slate-700 d-block">Tautan Referensi</label>
                            <a href="{{ $task->link }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-xl px-3">
                                <i class="fas fa-external-link-alt mr-2"></i> Buka Link
                            </a>
                        </div>
                    @endif

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Batas Waktu:</span>
                            <span class="font-weight-600 text-danger small">{{ \Carbon\Carbon::parse($task->due_date)->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Target Penerima:</span>
                            <span class="font-weight-600 text-slate-800 small">{{ $task->assigned_to === 'all_users' ? 'Semua Siswa' : 'Siswa Tertentu' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Dibuat Oleh:</span>
                            <span class="font-weight-600 text-slate-800 small">{{ $task->creator->name ?? 'Administrator' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submissions -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-file-invoice mr-2"></i>Pengumpulan Tugas</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                            <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                                <tr>
                                    <th class="border-0 px-4 py-3">Siswa</th>
                                    <th class="border-0 px-4 py-3">File / Jawaban</th>
                                    <th class="border-0 px-4 py-3">Status</th>
                                    <th class="border-0 px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-800 font-weight-500">
                                @forelse($task->submissions as $submission)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-weight-600 text-slate-800">{{ $submission->user->name }}</div>
                                            <small class="text-slate-600">NISN: {{ $submission->user->nisn }}</small>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($submission->image_path)
                                                <a href="{{ Storage::url($submission->image_path) }}" target="_blank" class="btn btn-light btn-sm rounded-xl mb-1 hover-translate-y transition-all">
                                                    <i class="fas fa-file-download mr-1"></i> Unduh File
                                                </a>
                                            @endif
                                            @if($submission->submission)
                                                <div class="small text-slate-600 bg-light p-2 rounded-lg mt-1" style="max-width: 250px;">{{ Str::limit($submission->submission, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($submission->status === 'approved')
                                                <span class="badge badge-pill bg-emerald-50 text-success px-3 py-2 font-weight-600">Disetujui</span>
                                            @elseif($submission->status === 'rejected')
                                                <span class="badge badge-pill bg-red-50 text-danger px-3 py-2 font-weight-600">Ditolak</span>
                                            @else
                                                <span class="badge badge-pill bg-blue-50 text-blue px-3 py-2 font-weight-600">Menunggu</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <form action="{{ route('admin.tasks.submissions.status', [$task, $submission]) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success btn-sm rounded-circle shadow-sm" {{ $submission->status === 'approved' ? 'disabled' : '' }}>
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.tasks.submissions.status', [$task, $submission]) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow-sm ml-1" {{ $submission->status === 'rejected' ? 'disabled' : '' }}>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <div class="mb-3"><i class="fas fa-file-alt fa-3x opacity-20"></i></div>
                                            <p class="mb-0 font-weight-500">Belum ada siswa yang mengumpulkan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-layout>
