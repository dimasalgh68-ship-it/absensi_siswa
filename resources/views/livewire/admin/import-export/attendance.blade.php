<div>
  <div class="row">
    @if ($mode != 'import')
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="fas fa-file-export mr-2"></i>Ekspor Data Absensi</h6>
          </div>
          <div class="card-body">
            <form wire:submit.prevent="export">
              <div class="form-group">
                <label for="year">
                  <i class="fas fa-calendar-alt text-primary"></i> Filter Per Tahun
                </label>
                <input type="number" 
                       class="form-control" 
                       id="year" 
                       name="year"
                       min="1970" 
                       max="2099" 
                       wire:model.live="year"
                       placeholder="2024">
              </div>
              
              <div class="form-group">
                <label for="month">
                  <i class="fas fa-calendar text-info"></i> Filter Per Bulan (Opsional)
                </label>
                <input type="month" 
                       class="form-control" 
                       id="month" 
                       name="month"
                       wire:model.live="month">
                <small class="form-text text-muted">Kosongkan untuk ekspor seluruh tahun</small>
              </div>
              
              <div class="form-group">
                <label for="division">
                  <i class="fas fa-building text-warning"></i> Filter Division (Opsional)
                </label>
                <select class="form-control" id="division" name="division" wire:model.live="division">
                  <option value="">Semua Division</option>
                  @foreach (App\Models\Division::all() as $division)
                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="form-group">
                <label for="jobTitle">
                  <i class="fas fa-briefcase text-success"></i> Filter Job Title (Opsional)
                </label>
                <select class="form-control" id="jobTitle" name="job_title" wire:model.live="job_title">
                  <option value="">Semua Job Title</option>
                  @foreach (App\Models\JobTitle::all() as $jobTitle)
                    <option value="{{ $jobTitle->id }}">{{ $jobTitle->name }}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="form-group">
                <label for="education">
                  <i class="fas fa-graduation-cap text-danger"></i> Filter Education (Opsional)
                </label>
                <select class="form-control" id="education" name="education" wire:model.live="education">
                  <option value="">Semua Education</option>
                  @foreach (App\Models\Education::all() as $education)
                    <option value="{{ $education->id }}">{{ $education->name }}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="d-flex flex-column gap-2">
                <button type="button" wire:click="preview" class="btn btn-secondary btn-block">
                  @if ($mode == 'export')
                    <i class="fas fa-times mr-1"></i> Batal
                  @else
                    <i class="fas fa-eye mr-1"></i> Preview
                  @endif
                </button>
                <button type="submit" class="btn btn-success btn-block" wire:loading.attr="disabled">
                  <i class="fas fa-download mr-1"></i>
                  <span wire:loading.remove>Ekspor ke Excel</span>
                  <span wire:loading>Mengekspor...</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endif
    
    @if ($mode != 'export')
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header bg-danger text-white">
            <h6 class="mb-0"><i class="fas fa-file-import mr-2"></i>Impor Data Absensi</h6>
          </div>
          <div class="card-body">
            <form x-data="{ file: null }" wire:submit.prevent="import" method="post" enctype="multipart/form-data">
              @csrf
              
              <div class="alert alert-info">
                <small>
                  <strong><i class="fas fa-info-circle"></i> Format File:</strong><br>
                  • Excel (.xlsx, .xls)<br>
                  • CSV (.csv)<br>
                  • OpenDocument (.ods)
                </small>
              </div>
              
              <div class="form-group">
                <label class="font-weight-bold">Pilih File:</label>
                <div class="custom-file">
                  <input type="file" 
                         class="custom-file-input" 
                         id="fileInputAttendance" 
                         x-ref="file"
                         x-on:change="file = $refs.file.files[0]" 
                         wire:model.live="file"
                         accept=".csv,.xls,.xlsx,.ods">
                  <label class="custom-file-label" for="fileInputAttendance" x-text="file ? file.name : 'Pilih file...'">
                    Pilih file...
                  </label>
                </div>
                @error('file')
                  <small class="text-danger d-block mt-2">{{ $message }}</small>
                @enderror
              </div>
              
              <div class="d-flex gap-2" x-show="file">
                <button type="button" 
                        class="btn btn-secondary flex-fill"
                        x-on:click.prevent="$refs.file.value = null; file = null; $wire.$set('file', null)">
                  <i class="fas fa-times mr-1"></i> Hapus File
                </button>
                <button type="submit" class="btn btn-danger flex-fill">
                  <i class="fas fa-upload mr-1"></i> Konfirmasi & Impor
                </button>
              </div>
              
              <div x-show="!file">
                <button type="button" class="btn btn-outline-danger btn-block" disabled>
                  <i class="fas fa-upload mr-1"></i> Pilih File Terlebih Dahulu
                </button>
              </div>
            </form>
            
            <hr>
            
            <div class="text-center">
              <a href="{{ route('admin.import-export.attendances.template') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-download mr-1"></i> Download Template Excel
              </a>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
  
  @if ($mode && $previewing)
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">
          <i class="fas fa-table mr-2"></i>Preview {{ ucfirst($mode) }}
        </h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <th class="text-center" style="width: 50px;">No</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>NISN</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Shift</th>
                <th>Barcode ID</th>
                <th>Koordinat</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Lampiran</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($attendances as $attendance)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $attendance->date?->format('Y-m-d') }}</td>
                  <td>{{ $attendance->user?->name }}</td>
                  <td>{{ $attendance->user?->nisn}}</td>
                  <td>{{ $attendance->time_in?->format('H:i:s') }}</td>
                  <td>{{ $attendance->time_out?->format('H:i:s') }}</td>
                  <td>{{ $attendance->shift?->name }}</td>
                  <td>{{ $attendance->barcode_id }}</td>
                  <td>
                    @if($attendance->lat_lng)
                      {{ $attendance->latitude }}, {{ $attendance->longitude }}
                    @else
                      -
                    @endif
                  </td>
                  <td>
                    <span class="badge badge-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : 'danger') }}">
                      {{ __($attendance->status) }}
                    </span>
                  </td>
                  <td>{{ Str::limit($attendance->note, 30) }}</td>
                  <td>
                    @if($attendance->attachment)
                      <img src="{{ $attendance->attachment }}" class="img-thumbnail" style="max-height: 100px;">
                    @else
                      -
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="12" class="text-center text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p class="mb-0">Tidak ada data untuk ditampilkan</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if($attendances && $attendances->count() > 0)
        <div class="card-footer">
          <small class="text-muted">
            <i class="fas fa-info-circle"></i> Total: <strong>{{ $attendances->count() }}</strong> data absensi
          </small>
        </div>
      @endif
    </div>
  @endif
</div>

<style>
.gap-2 > * + * {
    margin-left: 0.5rem;
}
</style>
