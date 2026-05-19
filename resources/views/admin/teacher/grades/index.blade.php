<x-teacher-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Input Nilai Siswa</h1>
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

    <!-- Filter Kelas & Mapel -->
    <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom-light">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-2"></i>Filter Kelas & Mata Pelajaran</h6>
        </div>
        <div class="card-body p-4 bg-slate-50">
            <form action="{{ route('admin.grades') }}" method="GET" class="row">
                <div class="col-md-5 mb-3 mb-md-0">
                    <label class="font-weight-600 text-slate-700" for="education_id">Pilih Kelas</label>
                    <select class="form-control rounded-xl" id="education_id" name="education_id" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($educations as $edu)
                            <option value="{{ $edu->id }}" {{ $selected_education == $edu->id ? 'selected' : '' }}>{{ $edu->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 mb-3 mb-md-0">
                    <label class="font-weight-600 text-slate-700" for="subject_id">Pilih Mata Pelajaran</label>
                    <select class="form-control rounded-xl" id="subject_id" name="subject_id" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" {{ $selected_subject == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block rounded-xl py-2 font-weight-bold shadow-sm hover-translate-y transition-all">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($selected_education && $selected_subject)
        <!-- Input Nilai -->
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom-light">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-edit mr-2"></i>Input Nilai Siswa</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.grades.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="subject_id" value="{{ $selected_subject }}">
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="font-weight-600 text-slate-700" for="semester">Semester</label>
                            <select class="form-control rounded-xl" id="semester" name="semester" required>
                                <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                                <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-600 text-slate-700" for="academic_year">Tahun Ajaran</label>
                            <input type="text" class="form-control rounded-xl" id="academic_year" name="academic_year" value="{{ old('academic_year', '2023/2024') }}" placeholder="Contoh: 2023/2024" required>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                            <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                                <tr>
                                    <th class="border-0 px-4 py-3">Nama Siswa</th>
                                    <th class="border-0 px-4 py-3">NISN</th>
                                    <th class="border-0 px-4 py-3" style="width: 200px;">Nilai Akhir (0-100)</th>
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
                                        <td class="px-4 py-3">
                                            <input type="number" class="form-control rounded-xl" name="grades[{{ $student->id }}]" min="0" max="100" step="0.1" value="{{ old('grades.'.$student->id, $grades->get($student->id)->score ?? '') }}" placeholder="0 - 100">
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

                    @if(count($students) > 0)
                        <button type="submit" class="btn btn-primary btn-block rounded-xl py-3 font-weight-bold shadow-sm hover-translate-y transition-all">
                            <i class="fas fa-save mr-2"></i> Simpan Semua Nilai
                        </button>
                    @endif
                </form>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4 p-5 text-center bg-white text-muted">
            <i class="fas fa-info-circle fa-3x opacity-20 mb-3 text-primary"></i>
            <h5 class="font-weight-bold text-slate-800">Pilih Kelas & Mata Pelajaran</h5>
            <p class="mb-0">Silakan pilih kelas dan mata pelajaran pada filter di atas untuk memulai penginputan nilai.</p>
        </div>
    @endif
</x-teacher-layout>
