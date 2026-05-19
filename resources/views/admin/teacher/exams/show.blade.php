<x-teacher-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Kelola Soal Ujian: {{ $exam->title }}</h1>
            <a href="{{ route('admin.exams') }}" class="btn btn-light btn-icon-split shadow-sm rounded-xl transition-all">
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
        <!-- Form Tambah Soal -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle mr-2"></i>Tambah Soal Pilihan Ganda</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.exams.questions.store', $exam) }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label class="font-weight-600 text-slate-700" for="question_text">Pertanyaan</label>
                            <textarea class="form-control rounded-xl" id="question_text" name="question_text" rows="4" placeholder="Tuliskan soal disini..." required></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-600 text-slate-700" for="option_a">Pilihan A</label>
                            <input type="text" class="form-control rounded-xl" id="option_a" name="option_a" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-600 text-slate-700" for="option_b">Pilihan B</label>
                            <input type="text" class="form-control rounded-xl" id="option_b" name="option_b" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-600 text-slate-700" for="option_c">Pilihan C</label>
                            <input type="text" class="form-control rounded-xl" id="option_c" name="option_c" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-600 text-slate-700" for="option_d">Pilihan D</label>
                            <input type="text" class="form-control rounded-xl" id="option_d" name="option_d" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-600 text-slate-700" for="option_e">Pilihan E (Optional)</label>
                            <input type="text" class="form-control rounded-xl" id="option_e" name="option_e">
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-600 text-slate-700" for="correct_option">Jawaban Benar</label>
                            <select class="form-control rounded-xl" id="correct_option" name="correct_option" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block rounded-xl py-3 font-weight-bold shadow-sm hover-translate-y transition-all">
                            <i class="fas fa-plus mr-2"></i> Tambahkan Soal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Soal -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list-ol mr-2"></i>Butir Soal ({{ $exam->questions->count() }})</h6>
                </div>
                <div class="card-body p-4">
                    @forelse($exam->questions as $index => $q)
                        <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="font-weight-bold text-slate-800 mb-0">No. {{ $index + 1 }}</h5>
                                <form action="{{ route('admin.exams.questions.destroy', [$exam, $q]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm" onclick="return confirm('Hapus soal ini?')">
                                        <i class="fas fa-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                            <p class="text-slate-800 font-weight-500 mb-3">{!! nl2br(e($q->question_text)) !!}</p>
                            
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <span class="font-weight-600 text-slate-700">A.</span> {{ $q->option_a }}
                                </div>
                                <div class="col-md-6 mb-2">
                                    <span class="font-weight-600 text-slate-700">B.</span> {{ $q->option_b }}
                                </div>
                                <div class="col-md-6 mb-2">
                                    <span class="font-weight-600 text-slate-700">C.</span> {{ $q->option_c }}
                                </div>
                                <div class="col-md-6 mb-2">
                                    <span class="font-weight-600 text-slate-700">D.</span> {{ $q->option_d }}
                                </div>
                                @if($q->option_e)
                                    <div class="col-md-6 mb-2">
                                        <span class="font-weight-600 text-slate-700">E.</span> {{ $q->option_e }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-2 text-success font-weight-bold">
                                <i class="fas fa-check-circle mr-1"></i> Kunci Jawaban: {{ $q->correct_option }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x opacity-20 mb-3"></i>
                            <p class="mb-0">Belum ada soal pada ujian ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-teacher-layout>
