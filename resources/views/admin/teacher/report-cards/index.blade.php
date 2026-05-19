<x-teacher-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Raport Semesteran</h1>
        </div>
    </x-slot>

    <!-- Filter Kelas -->
    <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom-light">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-2"></i>Pilih Kelas</h6>
        </div>
        <div class="card-body p-4 bg-slate-50">
            <form action="{{ route('admin.report-cards') }}" method="GET" class="row">
                <div class="col-md-10 mb-3 mb-md-0">
                    <select class="form-control rounded-xl" id="education_id" name="education_id" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($educations as $edu)
                            <option value="{{ $edu->id }}" {{ $selected_education == $edu->id ? 'selected' : '' }}>{{ $edu->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block rounded-xl py-2 font-weight-bold shadow-sm hover-translate-y transition-all">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($selected_education)
        <!-- Daftar Siswa -->
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom-light">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users mr-2"></i>Daftar Siswa Kelas</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                        <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                            <tr>
                                <th class="border-0 px-4 py-3">Nama Siswa</th>
                                <th class="border-0 px-4 py-3">NISN</th>
                                <th class="border-0 px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-800 font-weight-500">
                            @forelse($students as $student)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-weight-600 text-slate-800">{{ $student->name }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-slate-600">{{ $student->nisn }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('admin.report-cards.show', $student) }}" class="btn btn-light btn-sm rounded-xl px-3 hover-translate-y transition-all">
                                            <i class="fas fa-file-invoice-dollar text-primary mr-1"></i> Kelola Raport
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <p class="mb-0 font-weight-500">Tidak ada siswa yang terdaftar di kelas ini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4 p-5 text-center bg-white text-muted">
            <i class="fas fa-info-circle fa-3x opacity-20 mb-3 text-primary"></i>
            <h5 class="font-weight-bold text-slate-800">Pilih Kelas</h5>
            <p class="mb-0">Silakan pilih kelas pada filter di atas untuk mengelola raport semester siswa.</p>
        </div>
    @endif
</x-teacher-layout>
