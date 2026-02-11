<?php

namespace App\Livewire\Admin;

use App\Models\FaceRegistration;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class FaceRegistrationTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showDeleteModal = false;
    public $registrationToDelete = null;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDeletion($registrationId)
    {
        $this->registrationToDelete = $registrationId;
        $this->showDeleteModal = true;
    }

    public function deleteRegistration()
    {
        if ($this->registrationToDelete) {
            $registration = FaceRegistration::find($this->registrationToDelete);
            
            if ($registration) {
                // Delete photo file
                if ($registration->photo_path) {
                    Storage::disk('public')->delete($registration->photo_path);
                }
                
                // Soft delete by setting is_active to false
                $registration->update(['is_active' => false]);
                
                session()->flash('message', 'Registrasi wajah berhasil dihapus.');
            }
        }

        $this->showDeleteModal = false;
        $this->registrationToDelete = null;
    }

    public function render()
    {
        $registrations = FaceRegistration::with('user')
            ->where('is_active', true)
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('nisn', 'like', '%' . $this->search . '%');
                });
            })
            ->latest('registered_at')
            ->paginate($this->perPage);

        return view('livewire.admin.face-registration-table', [
            'registrations' => $registrations,
        ]);
    }
}
