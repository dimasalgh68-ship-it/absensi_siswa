<x-dynamic-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Edit Tugas</h1>
            <a href="{{ route('admin.tasks') }}" class="btn btn-light btn-icon-split shadow-sm rounded-xl transition-all">
                <span class="icon text-gray-600"><i class="fas fa-arrow-left"></i></span>
                <span class="text">Kembali</span>
            </a>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-edit mr-2"></i>Formulir Edit Tugas</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-4">
                            <label class="font-weight-600 text-slate-700" for="title">Judul Tugas</label>
                            <input type="text" class="form-control rounded-xl @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $task->title) }}" placeholder="Contoh: Tugas Mandiri Matematika" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-600 text-slate-700" for="description">Deskripsi / Instruksi Tugas</label>
                            <textarea class="form-control rounded-xl @error('description') is-invalid @enderror" id="description" name="description" rows="6" placeholder="Tuliskan instruksi tugas secara detail..." required>{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="assigned_to">Ditugaskan Kepada</label>
                                    <select class="form-control rounded-xl" id="assigned_to" name="assigned_to" onchange="toggleUserSelection(this.value)">
                                        <option value="all_users" {{ old('assigned_to', $task->assigned_to) === 'all_users' ? 'selected' : '' }}>Semua Siswa</option>
                                        <option value="specific_users" {{ old('assigned_to', $task->assigned_to) === 'specific_users' ? 'selected' : '' }}>Siswa Tertentu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="due_date">Batas Waktu (Deadline)</label>
                                    <input type="datetime-local" class="form-control rounded-xl @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d\TH:i') : '') }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @php
                            $assignedUserIds = $task->assignments->pluck('user_id')->toArray();
                        @endphp

                        <div id="specific-users-section" class="form-group mb-4 d-none">
                            <label class="font-weight-600 text-slate-700">Pilih Siswa</label>
                            <div class="border rounded-xl p-3 max-h-40 overflow-y-auto bg-slate-50">
                                @foreach($users as $user)
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="user-{{ $user->id }}" name="selected_users[]" value="{{ $user->id }}" {{ in_array($user->id, $assignedUserIds) ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-500" for="user-{{ $user->id }}">{{ $user->name }} (NISN: {{ $user->nisn }})</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('selected_users')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="image">Gambar / Lampiran Gambar (Optional)</label>
                                    @if($task->image_path)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($task->image_path) }}" alt="Task Image" class="img-thumbnail rounded-xl" style="max-height: 150px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-600 text-slate-700" for="link">Tautan Referensi (Optional)</label>
                                    <input type="url" class="form-control rounded-xl @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link', $task->link) }}" placeholder="https://example.com">
                                    @error('link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block rounded-xl py-3 font-weight-bold shadow-sm hover-translate-y transition-all">
                            <i class="fas fa-save mr-2"></i> Perbarui Tugas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleUserSelection(val) {
            const section = document.getElementById('specific-users-section');
            if (val === 'specific_users') {
                section.classList.remove('d-none');
            } else {
                section.classList.add('d-none');
            }
        }

        // Keep state on load
        document.addEventListener('DOMContentLoaded', function() {
            toggleUserSelection(document.getElementById('assigned_to').value);
        });
    </script>
</x-dynamic-layout>
