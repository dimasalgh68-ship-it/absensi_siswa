<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TeacherComponent extends Component
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

    # subject selection for creating/editing teacher
    public array $selectedSubjects = [];
    public $availableSubjects = [];

    public function mount()
    {
        // Only admin can access teacher data
        if (!\Auth::check() || !\Auth::user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses data guru.');
        }

        // Load available subjects
        $this->availableSubjects = \App\Models\Subject::all()->toArray();
    }

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
        $this->form->group = 'teacher';
        $this->form->password = 'password';
        $this->selectedSubjects = [];
    }

    public function create()
    {
        // Validate that at least one subject is selected
        if (empty($this->selectedSubjects)) {
            $this->addError('selectedSubjects', 'Mata pelajaran wajib dipilih minimal 1.');
            return;
        }

        $this->form->store();
        
        // Assign subjects to the newly created teacher
        $user = User::where('email', $this->form->email)->first();
        if ($user && $user->teacher) {
            $user->teacher->subjects()->sync($this->selectedSubjects);
        }
        
        $this->creating = false;
        $this->selectedSubjects = [];
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
        
        // Load teacher's current subjects
        if ($user->teacher) {
            $this->selectedSubjects = $user->teacher->subjects()->pluck('subject_id')->toArray();
        } else {
            $this->selectedSubjects = [];
        }
    }

    public function update()
    {
        // Validate that at least one subject is selected
        if (empty($this->selectedSubjects)) {
            $this->addError('selectedSubjects', 'Mata pelajaran wajib dipilih minimal 1.');
            return;
        }

        $this->form->update();
        
        // Update teacher's subjects
        if ($this->form->user && $this->form->user->teacher) {
            $this->form->user->teacher->subjects()->sync($this->selectedSubjects);
        }
        
        $this->editing = false;
        $this->selectedSubjects = [];
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
                $this->banner('Guru tidak ditemukan.', 'danger');
                return;
            }
            
            $userName = $user->name;
            $this->form->setUser($user)->delete();
            
            $this->confirmingDeletion = false;
            $this->selectedId = null;
            $this->deleteName = null;
            
            $this->banner("Guru {$userName} berhasil dihapus.");
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->confirmingDeletion = false;
            $this->banner('Anda tidak memiliki izin untuk menghapus guru ini.', 'danger');
            
        } catch (\Exception $e) {
            $this->confirmingDeletion = false;
            \Log::error('Delete user error: ' . $e->getMessage(), [
                'user_id' => $this->selectedId,
                'exception' => $e,
            ]);
            $this->banner('Terjadi kesalahan saat menghapus guru. Silakan coba lagi.', 'danger');
        }
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
        return User::where('group', 'teacher')
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
            $this->banner('Pilih minimal 1 guru untuk dihapus.', 'danger');
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
            
            $this->banner("{$count} guru berhasil dihapus.");
            
        } catch (\Exception $e) {
            $this->confirmingBulkDeletion = false;
            \Log::error('Bulk delete error: ' . $e->getMessage());
            $this->banner('Terjadi kesalahan saat menghapus guru. Silakan coba lagi.', 'danger');
        }
    }

    public function render()
    {
        $users = User::where('group', 'teacher')
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
        return view('livewire.admin.teachers', ['users' => $users]);
    }
}
