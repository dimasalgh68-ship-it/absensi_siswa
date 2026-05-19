<x-teacher-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Tambah Materi Baru</h1>
            <a href="{{ route('admin.materials') }}" class="btn btn-light btn-icon-split shadow-sm rounded-xl transition-all">
                <span class="icon text-gray-600"><i class="fas fa-arrow-left"></i></span>
                <span class="text">Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle mr-2"></i>Formulir Materi Baru</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label class="font-weight-600 text-slate-700" for="title">Judul Materi</label>
                            <input type="text" class="form-control rounded-xl @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Pengenalan Aljabar Linear" required>
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
                                    <label class="font-weight-600 text-slate-700" for="status">Status</label>
                                    <select class="form-control rounded-xl" id="status" name="status">
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-600 text-slate-700" for="content">Isi / Ringkasan Materi</label>
                            <textarea class="form-control rounded-xl @error('content') is-invalid @enderror" id="content" name="content" rows="6" placeholder="Tuliskan isi atau ringkasan materi pembelajaran..." required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-600 text-slate-700" for="file">Lampiran Dokumen (PDF, Word, Powerpoint, Zip)</label>
                            <input type="file" class="form-control-file @error('file') is-invalid @enderror" id="file" name="file">
                            <small class="text-muted">Maksimal 10MB</small>
                            @error('file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block rounded-xl py-3 font-weight-bold shadow-sm hover-translate-y transition-all">
                            <i class="fas fa-save mr-2"></i> Simpan Materi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-teacher-layout>
