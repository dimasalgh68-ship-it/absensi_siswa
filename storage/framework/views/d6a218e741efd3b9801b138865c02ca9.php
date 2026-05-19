<div>
  <div class="row">
    <!--[if BLOCK]><![endif]--><?php if($mode != 'import'): ?>
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
                
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['groups'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <small class="text-danger d-block mt-2"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
              </div>
              
              <div class="d-flex flex-column gap-2">
                <button type="button" wire:click="preview" class="btn btn-secondary btn-block">
                  <!--[if BLOCK]><![endif]--><?php if($mode == 'export'): ?>
                    <i class="fas fa-times mr-1"></i> Batal
                  <?php else: ?>
                    <i class="fas fa-eye mr-1"></i> Preview
                  <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </button>
                <button type="submit" class="btn btn-success btn-block">
                  <i class="fas fa-download mr-1"></i>
                  <?php echo e($mode == 'export' ? 'Konfirmasi & Ekspor' : 'Ekspor ke Excel'); ?>

                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <!--[if BLOCK]><![endif]--><?php if($mode != 'export'): ?>
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header bg-danger text-white">
            <h6 class="mb-0"><i class="fas fa-file-import mr-2"></i>Impor Data Siswa/Admin</h6>
          </div>
          <div class="card-body">
            <form x-data="{ file: null }" method="post" wire:submit.prevent="import" enctype="multipart/form-data">
              <?php echo csrf_field(); ?>
              
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
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <small class="text-danger d-block mt-2"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
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
              <a href="<?php echo e(route('admin.import-export.users.template')); ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-download mr-1"></i> Download Template Excel
              </a>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
  </div>
  
  <!--[if BLOCK]><![endif]--><?php if($mode && $previewing): ?>
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">
          <i class="fas fa-table mr-2"></i>Preview <?php echo e(ucfirst($mode)); ?>

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
              <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td class="text-center"><?php echo e($loop->iteration); ?></td>
                  <td><?php echo e($user->nisn); ?></td>
                  <td><?php echo e($user->name); ?></td>
                  <td><?php echo e($user->email); ?></td>
                  <td><?php echo e($user->phone); ?></td>
                  <td><?php echo e($user->gender); ?></td>
                  <td><?php echo e($user->birth_date?->format('Y-m-d')); ?></td>
                  <td><?php echo e(Str::limit($user->birth_place, 20)); ?></td>
                  <td><?php echo e(Str::limit($user->address, 50)); ?></td>
                  <td><?php echo e($user->city); ?></td>
                  <td><?php echo e($user->education?->name); ?></td>
                  <td><?php echo e($user->division?->name); ?></td>
                  <td><?php echo e($user->jobTitle?->name); ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="13" class="text-center text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p class="mb-0">Tidak ada data untuk ditampilkan</p>
                  </td>
                </tr>
              <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
          </table>
        </div>
      </div>
      <!--[if BLOCK]><![endif]--><?php if($users && $users->count() > 0): ?>
        <div class="card-footer">
          <small class="text-muted">
            <i class="fas fa-info-circle"></i> Total: <strong><?php echo e($users->count()); ?></strong> data
          </small>
        </div>
      <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
  <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>

<style>
.gap-2 > * + * {
    margin-left: 0.5rem;
}
</style>
<?php /**PATH C:\laragon\www\absensi-siswa\resources\views/livewire/admin/import-export/user.blade.php ENDPATH**/ ?>