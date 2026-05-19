<x-teacher-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Tambah Jadwal Baru</h1>
            <a href="{{ route('admin.schedules') }}" class="btn btn-light btn-icon-split shadow-sm rounded-xl transition-all">
                <span class="icon text-gray-600"><i class="fas fa-arrow-left"></i></span>
                <span class="text">Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle mr-2"></i>Formulir Jadwal Baru</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.schedules.store') }}" method="POST">
                        @csrf
                        
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
                                    <label class="font-weight-600 text-slate-700" for="education_id">Kelas</label>
                                    <select class="form-control rounded-xl" id="education_id" name="education_id" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($educations as $edu)
                                            <option value="{{ $edu->id }}" {{ old('education_id') == $edu->id ? 'selected' : '' }}>{{ $edu->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="teacher_id">Guru Pengajar</label>
                                    <select class="form-control rounded-xl" id="teacher_id" name="teacher_id" required>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id', auth()->id()) == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="day">Hari</label>
                                    <select class="form-control rounded-xl" id="day" name="day" required>
                                        <option value="">-- Pilih Hari --</option>
                                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                                            <option value="{{ $h }}" {{ old('day') === $h ? 'selected' : '' }}>{{ $h }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="start_time">Jam Mulai</label>
                                    <input type="time" class="form-control rounded-xl" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="end_time">Jam Selesai</label>
                                    <input type="time" class="form-control rounded-xl" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="room">Ruangan / Ruang Kelas</label>
                                    <input type="text" class="form-control rounded-xl" id="room" name="room" value="{{ old('room') }}" placeholder="Contoh: R. Laboratorium">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block rounded-xl py-3 font-weight-bold shadow-sm hover-translate-y transition-all">
                            <i class="fas fa-save mr-2"></i> Simpan Jadwal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-teacher-layout>
