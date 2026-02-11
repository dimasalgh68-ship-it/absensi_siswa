<div>
  <div class="row">
    @if ($mode != 'import')
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="fas fa-file-export mr-2"></i>Ekspor Data Siswa/Admin</h6>
          </div>
          <div class="card-body">
            <form wire:submit.prevent="export">
              <div class="form-group">
                <label class="font-weight-bold">Pilih Grup yang Akan Diekspor:</label>
                
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="user" value="user" wire:model.live="groups">
                  <label class="custom-control-label" for="user">Siswa</label>
                </div>
                
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="admin" value="admin" wire:model.live="groups">
                  <label class="custom-control-label" for="admin">Admin</label>
                </div>
                
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="superadmin" value="superadmin" wire:model.live="groups">
                  <label class="custom-control-label" for="superadmin">Super Admin</label>
                </div>
                
                @error('groups')
                  <small class="text-danger d-block mt-2">{{ $message }}</small>
                @enderror
              </div>
              
              <div class="d-flex flex-column gap-2">
                <button type="button" wire:click="preview" class="btn btn-secondary btn-block">
                  @if ($mode == 'export')
                    <i class="fas fa-times mr-1"></i> Batal
                  @else
                    <i class="fas fa-eye mr-1"></i> Preview
                  @endif
                </button>
                <button type="submit" class="btn btn-success btn-block">
                  <i class="fas fa-download mr-1"></i>
                  {{ $mode == 'export' ? 'Konfirmasi & Ekspor' : 'Ekspor ke Excel' }}
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
            <h6 class="mb-0"><i class="fas fa-file-import mr-2"></i>Impor Data Siswa/Admin</h6>
          </div>
          <div class="card-body">
            <form x-data="{ file: null }" method="post" wire:submit.prevent="import" enctype="multipart/form-data">
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
                         id="fileInput" 
                         x-ref="file"
                         x-on:change="file = $refs.file.files[0]" 
                         wire:model.live="file"
                         accept=".csv,.xls,.xlsx,.ods">
                  <label class="custom-file-label" for="fileInput" x-text="file ? file.name : 'Pilih file...'">
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
              <a href="{{ route('admin.import-export.users.template') }}" class="btn btn-sm btn-outline-primary">
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
                <th>NISN</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Gender</th>
                <th>Tanggal Lahir</th>
                <th>Tempat Lahir</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>Pendidikan</th>
                <th>Angkatan</th>
                <th>Jabatan</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $user)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $user->nisn }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->phone }}</td>
                  <td>{{ $user->gender }}</td>
                  <td>{{ $user->birth_date?->format('Y-m-d') }}</td>
                  <td>{{ Str::limit($user->birth_place, 20) }}</td>
                  <td>{{ Str::limit($user->address, 50) }}</td>
                  <td>{{ $user->city }}</td>
                  <td>{{ $user->education?->name }}</td>
                  <td>{{ $user->division?->name }}</td>
                  <td>{{ $user->jobTitle?->name }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="13" class="text-center text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p class="mb-0">Tidak ada data untuk ditampilkan</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if($users && $users->count() > 0)
        <div class="card-footer">
          <small class="text-muted">
            <i class="fas fa-info-circle"></i> Total: <strong>{{ $users->count() }}</strong> data
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
