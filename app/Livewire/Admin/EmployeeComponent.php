<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class EmployeeComponent extends Component
{
    use WithPagination, InteractsWithBanner, WithFileUploads;

    public UserForm $form;
    public $deleteName = null;
    public $creating = false;
    public $editing = false;
    public $confirmingDeletion = false;
    public $selectedId = null;
    public $showDetail = null;

    # filter
    public ?string $division = null;
    public ?string $jobTitle = null;
    public ?string $education = null;
    public ?string $search = null;

    # bulk selection
    public array $selectedRows = [];
    public bool $selectAll = false;
    public bool $confirmingBulkDeletion = false;

    public function show($id)
    {
        $this->form->setUser(User::find($id));
        $this->showDetail = true;
    }

    public function showCreating()
    {
        $this->form->resetErrorBag();
        $this->form->reset();
        $this->creating = true;
        $this->form->group = 'student';
        $this->form->password = 'password';
    }

    public function create()
    {
        $this->form->store();
        $this->creating = false;
        $this->banner(__('Created successfully.'));
    }

    public function edit($id)
    {
        $this->form->resetErrorBag();
        $this->form->reset();
        $this->editing = true;
        /** @var User $user */
        $user = User::find($id);
        $this->form->setUser($user);
    }

    public function update()
    {
        $this->form->update();
        $this->editing = false;
        $this->banner(__('Updated successfully.'));
    }

    public function deleteProfilePhoto()
    {
        $this->form->deleteProfilePhoto();
    }

    public function confirmDeletion($id, $name)
    {
        $this->deleteName = $name;
        $this->confirmingDeletion = true;
        $this->selectedId = $id;
    }

    public function delete()
    {
        try {
            $user = User::find($this->selectedId);
            
            if (!$user) {
                $this->confirmingDeletion = false;
                $this->banner('Siswa tidak ditemukan.', 'danger');
                return;
            }
            
            $userName = $user->name;
            $this->form->setUser($user)->delete();
            
            $this->confirmingDeletion = false;
            $this->selectedId = null;
            $this->deleteName = null;
            
            $this->banner("Siswa {$userName} berhasil dihapus.");
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->confirmingDeletion = false;
            $this->banner('Anda tidak memiliki izin untuk menghapus siswa ini.', 'danger');
            
        } catch (\Exception $e) {
            $this->confirmingDeletion = false;
            \Log::error('Delete user error: ' . $e->getMessage(), [
                'user_id' => $this->selectedId,
                'exception' => $e,
            ]);
            $this->banner('Terjadi kesalahan saat menghapus siswa. Silakan coba lagi.', 'danger');
        }
<<<<<<< HEAD
    }

    public function toggleSelectAll($checked)
    {
        if ($checked) {
            $this->selectedRows = $this->getFilteredUserIds();
        } else {
            $this->selectedRows = [];
        }
    }

    protected function getFilteredUserIds()
    {
        return User::where('group', 'student')
            ->when($this->search, function (Builder $q) {
                return $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->when($this->division, fn (Builder $q) => $q->where('division_id', $this->division))
            ->when($this->jobTitle, fn (Builder $q) => $q->where('job_title_id', $this->jobTitle))
            ->when($this->education, fn (Builder $q) => $q->where('education_id', $this->education))
            ->pluck('id')
            ->toArray();
    }

    public function confirmBulkDeletion()
    {
        if (empty($this->selectedRows)) {
            $this->banner('Pilih minimal 1 siswa untuk dihapus.', 'danger');
            return;
        }
        $this->confirmingBulkDeletion = true;
    }

    public function bulkDelete()
    {
        try {
            $count = count($this->selectedRows);
            
            User::whereIn('id', $this->selectedRows)->delete();
            
            $this->selectedRows = [];
            $this->selectAll = false;
            $this->confirmingBulkDeletion = false;
            
            $this->banner("{$count} siswa berhasil dihapus.");
            
        } catch (\Exception $e) {
            $this->confirmingBulkDeletion = false;
            \Log::error('Bulk delete error: ' . $e->getMessage());
            $this->banner('Terjadi kesalahan saat menghapus siswa. Silakan coba lagi.', 'danger');
        }
=======
>>>>>>> b7451b4dfb32aa6d059cb1d176141e6ab49a7ffd
    }

    public function render()
    {
        $users = User::where('group', 'student',)
            ->when($this->search, function (Builder $q) {
                return $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->when($this->division, fn (Builder $q) => $q->where('division_id', $this->division))
            ->when($this->jobTitle, fn (Builder $q) => $q->where('job_title_id', $this->jobTitle))
            ->when($this->education, fn (Builder $q) => $q->where('education_id', $this->education))
            ->orderBy('name')
            ->paginate(20);
        return view('livewire.admin.employees', ['users' => $users]);
    }
}
