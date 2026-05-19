<?php

namespace App\Livewire\Admin;

use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\Attributes\Computed;

class TeacherSubjectComponent extends Component
{
    use InteractsWithBanner;

    public $teacher_id;
    public $selectedSubjects = [];
    public $availableSubjects = [];
    public $teacherSubjects = [];
    public $showModal = false;
    public $selectedTeacher = null;
    public $search = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    public function mount()
    {
        // Only admin can manage teacher subjects
        if (!Auth::check() || !Auth::user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk mengelola mata pelajaran guru.');
        }

        $this->availableSubjects = Subject::all()->toArray();
    }

    #[Computed]
    public function filteredTeachers()
    {
        $query = Teacher::with('user', 'subjects');

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $teachers = $query->get();

        // Sort
        if ($this->sortBy === 'name') {
            $teachers = $teachers->sortBy(function ($teacher) {
                return $teacher->user?->name ?? '';
            }, SORT_REGULAR, $this->sortDirection === 'desc');
        } elseif ($this->sortBy === 'subjects_count') {
            $teachers = $teachers->sortBy(function ($teacher) {
                return $teacher->subjects->count();
            }, SORT_REGULAR, $this->sortDirection === 'desc');
        }

        return $teachers;
    }

    public function selectTeacher($teacherId)
    {
        $this->teacher_id = $teacherId;
        $teacher = Teacher::find($teacherId);
        
        if (!$teacher) {
            $this->banner('Guru tidak ditemukan.', 'error');
            return;
        }

        $this->selectedTeacher = $teacher;
        $this->teacherSubjects = $teacher->subjects()->pluck('subject_id')->map(fn($id) => (int)$id)->toArray();
        $this->selectedSubjects = $this->teacherSubjects;
        $this->showModal = true;
    }

    public function toggleSubject($subjectId)
    {
        if (in_array($subjectId, $this->selectedSubjects)) {
            $this->selectedSubjects = array_filter(
                $this->selectedSubjects,
                fn($id) => $id != $subjectId
            );
        } else {
            $this->selectedSubjects[] = $subjectId;
        }
    }

    public function addSubjectQuick($teacherId, $subjectId)
    {
        $teacher = Teacher::find($teacherId);
        
        if (!$teacher) {
            $this->banner('Guru tidak ditemukan.', 'error');
            return;
        }

        // Check if subject already assigned
        if ($teacher->subjects()->where('subject_id', $subjectId)->exists()) {
            $this->banner('Mata pelajaran sudah ditugaskan ke guru ini.', 'warning');
            return;
        }

        $teacher->subjects()->attach($subjectId);
        $this->banner('Mata pelajaran berhasil ditambahkan.');
    }

    public function removeSubjectQuick($teacherId, $subjectId)
    {
        $teacher = Teacher::find($teacherId);
        
        if (!$teacher) {
            $this->banner('Guru tidak ditemukan.', 'error');
            return;
        }

        $teacher->subjects()->detach($subjectId);
        $this->banner('Mata pelajaran berhasil dihapus.');
    }

    public function removeAllSubjects($teacherId)
    {
        $teacher = Teacher::find($teacherId);
        
        if (!$teacher) {
            $this->banner('Guru tidak ditemukan.', 'error');
            return;
        }

        $teacher->subjects()->detach();
        $this->banner('Semua mata pelajaran berhasil dihapus.');
    }

    public function saveSubjects()
    {
        if (!$this->teacher_id) {
            $this->banner('Guru tidak dipilih.', 'error');
            return;
        }

        $teacher = Teacher::find($this->teacher_id);
        
        if (!$teacher) {
            $this->banner('Guru tidak ditemukan.', 'error');
            return;
        }

        // Ensure selectedSubjects are integers
        $subjectsToSync = array_map(fn($id) => (int)$id, $this->selectedSubjects);

        // Sync the subjects (this will add new and remove old ones)
        $teacher->subjects()->sync($subjectsToSync);

        $this->showModal = false;
        $this->banner('Mata pelajaran guru berhasil diperbarui.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->teacher_id = null;
        $this->selectedTeacher = null;
        $this->selectedSubjects = [];
    }

    public function setSortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        return view('livewire.admin.teacher-subject-component', [
            'teachers' => $this->filteredTeachers,
            'availableSubjects' => $this->availableSubjects,
        ]);
    }
}
