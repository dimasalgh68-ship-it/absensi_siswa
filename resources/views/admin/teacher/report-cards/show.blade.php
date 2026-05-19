<x-teacher-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Kelola Raport: {{ $student->name }}</h1>
            <a href="{{ route('admin.report-cards') }}" class="btn btn-light btn-icon-split shadow-sm rounded-xl transition-all">
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

    <!-- Filter Semester & Tahun Ajaran -->
    <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
        <div class="card-body p-4 bg-slate-50">
            <form action="{{ route('admin.report-cards.show', $student) }}" method="GET" class="row">
                <div class="col-md-5 mb-3 mb-md-0">
                    <label class="font-weight-600 text-slate-700" for="semester">Semester</label>
                    <select class="form-control rounded-xl" id="semester" name="semester" onchange="this.form.submit()">
                        <option value="1" {{ $semester == 1 ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                        <option value="2" {{ $semester == 2 ? 'selected' : '' }}>Semester 2 (Genap)</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="font-weight-600 text-slate-700" for="academic_year">Tahun Ajaran</label>
                    <select class="form-control rounded-xl" id="academic_year" name="academic_year" onchange="this.form.submit()">
                        <option value="2023/2024" {{ $academic_year === '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                        <option value="2024/2025" {{ $academic_year === '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    @php
        $total = $grades->sum('score');
        $count = $grades->count();
        $average = $count > 0 ? round($total / $count, 2) : 0;
    @endphp

    <div class="row">
        <!-- Rincian Nilai -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list mr-2"></i>Rincian Nilai Mapel</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0" style="width: 100%;">
                            <thead class="bg-slate-50 text-slate-600 extra-small font-weight-bold">
                                <tr>
                                    <th class="border-0 px-4 py-3">Mata Pelajaran</th>
                                    <th class="border-0 px-4 py-3 text-right">Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-800 font-weight-500">
                                @forelse($grades as $grade)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-weight-600 text-slate-800">{{ $grade->subject->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="badge badge-pill bg-blue-50 text-blue font-weight-bold px-3 py-2">
                                                {{ $grade->score }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5 text-muted">
                                            <p class="mb-0">Belum ada nilai yang diinputkan untuk semester ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan & Catatan Raport -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-premium rounded-2xl overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calculator mr-2"></i>Kalkulasi & Catatan Raport</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center mb-4">
                        <div class="col-6 border-right">
                            <h2 class="font-weight-bold text-slate-800">{{ $total }}</h2>
                            <small class="text-muted font-weight-600">Total Nilai</small>
                        </div>
                        <div class="col-6">
                            <h2 class="font-weight-bold text-primary">{{ $average }}</h2>
                            <small class="text-muted font-weight-600">Nilai Rata-rata</small>
                        </div>
                    </div>

                    <form action="{{ route('admin.report-cards.store', $student) }}" method="POST">
                        @csrf
                        <input type="hidden" name="semester" value="{{ $semester }}">
                        <input type="hidden" name="academic_year" value="{{ $academic_year }}">
                        <input type="hidden" name="total_score" value="{{ $total }}">
                        <input type="hidden" name="average_score" value="{{ $average }}">

                        <div class="form-group mb-4">
                            <label class="font-weight-600 text-slate-700" for="notes">Catatan Perkembangan Siswa</label>
                            <textarea class="form-control rounded-xl @error('notes') is-invalid @enderror" id="notes" name="notes" rows="6" placeholder="Tuliskan catatan perkembangan karakter, sikap, dan akademik siswa..." required>{{ old('notes', $reportCard->notes ?? '') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block rounded-xl py-3 font-weight-bold shadow-sm hover-translate-y transition-all" {{ $count == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-save mr-2"></i> Simpan & Terbitkan Raport
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-teacher-layout>
