<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class SubjectComponent extends Component
{
    use InteractsWithBanner;

    public $name;
    public $code;
    public $deleteName = null;
    public $creating = false;
    public $editing = false;
    public $confirmingDeletion = false;
    public $selectedId = null;

    protected $rules = [
        'name' => ['required', 'string', 'max:255', 'unique:subjects,name'],
        'code' => ['required', 'string', 'max:10', 'unique:subjects,code'],
    ];

    public function showCreating()
    {
        $this->resetErrorBag();
        $this->reset();
        $this->creating = true;
    }

    public function create()
    {
        // SECURITY: Only admin can create subjects
        if (Auth::user()->isNotAdmin) {
            return abort(403, 'Anda tidak memiliki izin untuk membuat mata pelajaran.');
        }
        
        $this->validate();
        Subject::create([
            'name' => $this->name,
            'code' => $this->code,
        ]);
        $this->creating = false;
        $this->name = null;
        $this->code = null;
        $this->banner(__('Created successfully.'));
    }

    public function edit($id)
    {
        // SECURITY: Only admin can edit subjects
        if (Auth::user()->isNotAdmin) {
            return abort(403, 'Anda tidak memiliki izin untuk mengedit mata pelajaran.');
        }
        
        $this->resetErrorBag();
        $this->editing = true;
        $subject = Subject::find($id);
        $this->name = $subject->name;
        $this->code = $subject->code;
        $this->selectedId = $id;
    }

    public function update()
    {
        // SECURITY: Only admin can update subjects
        if (Auth::user()->isNotAdmin) {
            return abort(403, 'Anda tidak memiliki izin untuk mengubah mata pelajaran.');
        }
        
        $this->validate();
        $subject = Subject::find($this->selectedId);
        $subject->update([
            'name' => $this->name,
            'code' => $this->code,
        ]);
        $this->editing = false;
        $this->selectedId = null;
        $this->banner(__('Updated successfully.'));
    }

    public function confirmDeletion($id, $name)
    {
        $this->deleteName = $name;
        $this->confirmingDeletion = true;
        $this->selectedId = $id;
    }

    public function delete()
    {
        // SECURITY: Only admin can delete subjects
        if (Auth::user()->isNotAdmin) {
            return abort(403, 'Anda tidak memiliki izin untuk menghapus mata pelajaran.');
        }
        
        $subject = Subject::find($this->selectedId);
        $subject->delete();
        $this->confirmingDeletion = false;
        $this->selectedId = null;
        $this->deleteName = null;
        $this->banner(__('Deleted successfully.'));
    }

    public function render()
    {
        $subjects = Subject::all();
        return view('livewire.admin.master-data.subject', ['subjects' => $subjects]);
    }
}
