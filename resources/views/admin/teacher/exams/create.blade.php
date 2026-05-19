<x-teacher-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Buat Ujian CBT Baru</h1>
            <a href="{{ route('admin.exams') }}" class="btn btn-light btn-icon-split shadow-sm rounded-xl transition-all">
                <span class="icon text-gray-600"><i class="fas fa-arrow-left"></i></span>
                <span class="text">Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle mr-2"></i>Formulir Ujian CBT Baru</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.exams.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label class="font-weight-600 text-slate-700" for="title">Judul Ujian</label>
                            <input type="text" class="form-control rounded-xl @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Ujian Tengah Semester Matematika" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="subject_id">Mata Pelajaran</label>
                                    <select class="form-control rounded-xl" id="subject_id" name="subject_id" required>
                                        <option value="">-- Pilih Mata Pelajaran --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="duration">Durasi (Menit)</label>
                                    <input type="number" class="form-control rounded-xl @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration') }}" placeholder="Contoh: 90" required>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="start_time">Waktu Mulai Ujian</label>
                                    <input type="datetime-local" class="form-control rounded-xl @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="end_time">Waktu Selesai Ujian</label>
                                    <input type="datetime-local" class="form-control rounded-xl @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block rounded-xl py-3 font-weight-bold shadow-sm hover-translate-y transition-all">
                            <i class="fas fa-save mr-2"></i> Simpan Ujian & Lanjutkan Buat Soal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-teacher-layout>
