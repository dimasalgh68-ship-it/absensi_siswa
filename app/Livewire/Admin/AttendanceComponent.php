<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\AttendanceDetailTrait;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithPagination;


class AttendanceComponent extends Component
{
    use AttendanceDetailTrait;
    use WithPagination, InteractsWithBanner;

    # filter
    public ?string $month;
    public ?string $week = null;
    public ?string $date = null;
    public ?string $division = null;
    public ?string $jobTitle = null;
    public ?string $education = null;
    public ?string $search = null;
    public array $selectedRows = [];

    # edit modal
    public bool $showEditModal = false;
    public ?int $editingAttendanceId = null;
    public ?string $editDate = null;
    public ?string $editTimeIn = null;
    public ?string $editTimeOut = null;
    public ?string $editStatus = null;
    public ?string $editNote = null;
    public ?int $editScheduleId = null;

    # create modal
    public bool $showCreateModal = false;
    public ?string $createUserId = null;
    public ?string $createDate = null;
    public ?string $createTimeIn = null;
    public ?string $createTimeOut = null;
    public ?string $createStatus = 'present';
    public ?string $createNote = null;
    public ?int $createScheduleId = null;

    public function mount()
    {
        $this->date = today()->format('Y-m-d');
    }

    public function updating($key): void
    {
        if ($key === 'search' || $key === 'division' || $key === 'jobTitle' || $key === 'education') {
            $this->resetPage();
        }
        if ($key === 'month') {
            $this->resetPage();
            $this->week = null;
            $this->date = null;
        }
        if ($key === 'week') {
            $this->resetPage();
            $this->month = null;
            $this->date = null;
        }
        if ($key === 'date') {
            $this->resetPage();
            $this->month = null;
            $this->week = null;
            $this->selectedRows = [];
        }
    }

    public function show($attendanceId)
    {
        /** @var Attendance */
        $attendance = Attendance::find($attendanceId);
        if ($attendance) {
            $this->showDetail = true;
            $this->currentAttendance = $attendance->getAttributes();
            $this->currentAttendance['name'] = $attendance->user->name;
            $this->currentAttendance['nisn'] = $attendance->user->nisn;
            $this->currentAttendance['address'] = $attendance->user->address;
            
            // Face recognition data (Clock In)
            if ($attendance->face_photo_path) {
                $this->currentAttendance['face_photo_url'] = \Storage::url($attendance->face_photo_path);
            }
            if ($attendance->face_similarity_score) {
                $this->currentAttendance['face_similarity_score'] = $attendance->face_similarity_score;
            }
            
            // Face recognition data (Clock Out)
            if ($attendance->face_photo_out_path) {
                $this->currentAttendance['face_photo_out_url'] = \Storage::url($attendance->face_photo_out_path);
            }
            if ($attendance->face_similarity_score_out) {
                $this->currentAttendance['face_similarity_score_out'] = $attendance->face_similarity_score_out;
            }
            
            if ($attendance->validation_method) {
                $this->currentAttendance['validation_method'] = $attendance->validation_method;
            }
            
            if ($attendance->attachment) {
                $this->currentAttendance['attachment'] = $attendance->attachment_url;
            }
            if ($attendance->shift_id) {
                $this->currentAttendance['shift'] = $attendance->shift;
            }
        }
    }

    public function edit($attendanceId)
    {
        /** @var Attendance */
        $attendance = Attendance::find($attendanceId);
        
        if ($attendance) {
            $this->editingAttendanceId = $attendance->id;
            $this->editDate = $attendance->date->format('Y-m-d');
            $this->editTimeIn = $attendance->time_in ? $attendance->time_in->format('H:i') : null;
            $this->editTimeOut = $attendance->time_out ? $attendance->time_out->format('H:i') : null;
            $this->editStatus = $attendance->status;
            $this->editNote = $attendance->note;
            $this->editScheduleId = $attendance->schedule_id;
            $this->showEditModal = true;
        }
    }

    public function updateAttendance()
    {
        $this->validate([
            'editDate' => 'required|date',
            'editTimeIn' => 'nullable|date_format:H:i',
            'editTimeOut' => 'nullable|date_format:H:i',
            'editStatus' => 'required|in:present,late,excused,sick,absent',
            'editNote' => 'nullable|string|max:255',
            'editScheduleId' => 'nullable|exists:shifts,id',
        ]);

        try {
            /** @var Attendance */
            $attendance = Attendance::find($this->editingAttendanceId);
            
            if ($attendance) {
                $oldDate = $attendance->date->format('Y-m-d');
                
                // Prevent duplicate date for same user
                if ($this->editDate != $oldDate) {
                    $exists = Attendance::where('user_id', $attendance->user_id)
                        ->whereDate('date', $this->editDate)
                        ->where('id', '!=', $attendance->id)
                        ->exists();
                    
                    if ($exists) {
                        $this->banner('Data absensi untuk tanggal tersebut sudah ada!', 'danger');
                        return;
                    }
                }

                $attendance->update([
                    'date' => $this->editDate,
                    'time_in' => $this->editTimeIn ?: null,
                    'time_out' => $this->editTimeOut ?: null,
                    'status' => $this->editStatus,
                    'note' => $this->editNote,
                    'shift_id' => $this->editScheduleId,
                ]);

                // Clear cache for old and new dates
                Attendance::clearUserAttendanceCache($attendance->user, Carbon::parse($oldDate));
                if ($oldDate != $this->editDate) {
                    Attendance::clearUserAttendanceCache($attendance->user, Carbon::parse($this->editDate));
                }

                $this->banner('Data absensi berhasil diperbarui!');
                $this->closeEditModal();
                $this->dispatch('refresh');
            } else {
                $this->banner('Data absensi tidak ditemukan!', 'danger');
                $this->closeEditModal();
            }
        } catch (\Exception $e) {
            $this->banner('Gagal memperbarui data absensi: ' . $e->getMessage(), 'danger');
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingAttendanceId = null;
        $this->editDate = null;
        $this->editTimeIn = null;
        $this->editTimeOut = null;
        $this->editStatus = null;
        $this->editNote = null;
        $this->editScheduleId = null;
        $this->resetValidation();
    }

    public function deleteAttendance($attendanceId)
    {
        try {
            /** @var Attendance */
            $attendance = Attendance::find($attendanceId);
            
            if ($attendance) {
                $user = $attendance->user;
                $date = $attendance->date;
                
                $attendance->delete();
                
                // Clear cache
                Attendance::clearUserAttendanceCache($user, Carbon::parse($date));
                
                $this->banner('Data absensi berhasil dihapus!');
                $this->dispatch('refresh');
            }
        } catch (\Exception $e) {
            $this->banner('Gagal menghapus data absensi: ' . $e->getMessage());
        }
    }

    /**
     * Quick update status for existing attendance
     */
    public function updateStatus($attendanceId, $status)
    {
        // Validate status
        if (!in_array($status, ['present', 'late', 'excused', 'sick', 'absent'])) {
            $this->banner('Status tidak valid!');
            return;
        }

        try {
            /** @var Attendance */
            $attendance = Attendance::find($attendanceId);
            
            if ($attendance) {
                $attendance->update([
                    'status' => $status,
                ]);

                // Clear cache
                Attendance::clearUserAttendanceCache($attendance->user, Carbon::parse($attendance->date));

                // Update current detail if open
                if ($this->showDetail && isset($this->currentAttendance['id']) && $this->currentAttendance['id'] == $attendanceId) {
                    $this->currentAttendance['status'] = $status;
                }

                $this->banner('Status absensi berhasil diperbarui!');
                
                // Refresh data manually by resetting the employee cache in case pagination is stale
                $this->dispatch('refresh');
            }
        } catch (\Exception $e) {
            $this->banner('Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Create attendance with status for students who haven't checked in
     */
    public function createAttendanceWithStatus($userId, $status)
    {
        // Validate status
        if (!in_array($status, ['present', 'late', 'excused', 'sick', 'absent'])) {
            $this->banner('Status tidak valid!');
            return;
        }

        try {
            $date = $this->date ?? today()->format('Y-m-d');
            
            // Check if attendance already exists
            $existing = Attendance::where('user_id', $userId)
                ->whereDate('date', $date)
                ->first();

            if ($existing) {
                $this->banner('Data absensi untuk tanggal ini sudah ada!');
                return;
            }

            // Try to pick a default schedule
            $scheduleId = Shift::first()?->id;

            // Create attendance with status only
            Attendance::create([
                'user_id' => $userId,
                'date' => $date,
                'status' => $status,
                'shift_id' => $scheduleId,
                'validation_method' => 'manual',
                'note' => 'Absensi manual oleh admin',
            ]);

            // Clear cache
            Attendance::clearUserAttendanceCache(User::find($userId), Carbon::parse($date));

            $this->banner('Status absensi berhasil ditambahkan!');
            
            // Refresh data manually
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->banner('Gagal menambahkan status: ' . $e->getMessage());
        }
    }

    public function openCreateModal($userId)
    {
        $this->createUserId = $userId;
        $this->createDate = $this->date ?? today()->format('Y-m-d');
        $this->createStatus = 'present';
        $this->createScheduleId = Shift::first()?->id;
        $this->showCreateModal = true;
    }

    public function createAttendance()
    {
        $this->validate([
            'createUserId' => 'required|exists:users,id',
            'createDate' => 'required|date',
            'createTimeIn' => 'nullable|date_format:H:i',
            'createTimeOut' => 'nullable|date_format:H:i',
            'createStatus' => 'required|in:present,late,excused,sick,absent',
            'createNote' => 'nullable|string|max:255',
            'createScheduleId' => 'nullable|exists:shifts,id',
        ]);

        try {
            // Check if already exists
            $exists = Attendance::where('user_id', $this->createUserId)
                ->whereDate('date', $this->createDate)
                ->exists();
            
            if ($exists) {
                $this->banner('Data absensi untuk siswa ini pada tanggal tersebut sudah ada!', 'danger');
                return;
            }
            Attendance::create([
                'user_id' => $this->createUserId,
                'date' => $this->createDate,
                'time_in' => $this->createTimeIn,
                'time_out' => $this->createTimeOut,
                'status' => $this->createStatus,
                'note' => $this->createNote ?? 'Absensi manual oleh admin',
                'shift_id' => $this->createScheduleId,
                'validation_method' => 'manual',
            ]);

            Attendance::clearUserAttendanceCache(User::find($this->createUserId), Carbon::parse($this->createDate));

            $this->banner('Data absensi manual berhasil ditambahkan!');
            $this->closeCreateModal();
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->banner('Gagal menambahkan data absensi: ' . $e->getMessage());
        }
    }

    public function toggleSelectAll($checked)
    {
        if ($checked) {
            $this->selectedRows = $this->getFilteredEmployeeIds();
        } else {
            $this->selectedRows = [];
        }
    }

    protected function getFilteredEmployeeIds()
    {
        $query = User::query()->where('group', 'student');

        if ($this->division) {
            $query->where('division_id', $this->division);
        }

        if ($this->jobTitle) {
            $query->where('job_title_id', $this->jobTitle);
        }

        if ($this->education) {
            $query->where('education_id', $this->education);
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query->pluck('id')->toArray();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->createUserId = null;
        $this->createDate = null;
        $this->createTimeIn = null;
        $this->createTimeOut = null;
        $this->createStatus = 'present';
        $this->createNote = null;
        $this->createScheduleId = null;
        $this->resetValidation();
    }

    /**
     * Batch update status for selected employees
     */
    public function batchSetStatus($status)
    {
        if (empty($this->selectedRows)) {
            $this->banner('Pilih siswa terlebih dahulu!', 'danger');
            return;
        }

        if (!in_array($status, ['present', 'late', 'excused', 'sick', 'absent'])) {
            $this->banner('Status tidak valid!', 'danger');
            return;
        }

        $date = $this->date ?? today()->format('Y-m-d');
        $scheduleId = Shift::first()?->id;
        $count = 0;

        try {
            // Pre-load all users in selected rows to avoid N+1 and check existence
            $users = User::whereIn('id', $this->selectedRows)->get();
            
            foreach ($users as $user) {
                $attendance = Attendance::where('user_id', $user->id)
                    ->whereDate('date', $date)
                    ->first();

                if ($attendance) {
                    $attendance->update([
                        'status' => $status,
                        'validation_method' => $attendance->validation_method ?? 'manual'
                    ]);
                } else {
                    Attendance::create([
                        'user_id' => $user->id,
                        'date' => $date,
                        'status' => $status,
                        'shift_id' => $scheduleId,
                        'validation_method' => 'manual',
                        'note' => 'Absensi massal oleh admin',
                    ]);
                }

                Attendance::clearUserAttendanceCache($user, Carbon::parse($date));
                $count++;
            }

            $this->selectedRows = [];
            $this->banner("Berhasil memperbarui status untuk $count siswa!");
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->banner('Gagal memperbarui status secara massal: ' . $e->getMessage(), 'danger');
        }
    }

    public function render()
    {
        $dates = [];
        
        // Ensure we have a default filter
        if (!$this->date && !$this->week && !$this->month) {
            $this->date = today()->format('Y-m-d');
        }
        
        if ($this->date) {
            $dates = [Carbon::parse($this->date)];
        } else if ($this->week) {
            $start = Carbon::parse($this->week)->startOfWeek();
            $end = Carbon::parse($this->week)->endOfWeek();
            $dates = $start->range($end)->toArray();
        } else if ($this->month) {
            $start = Carbon::parse($this->month)->startOfMonth();
            $end = Carbon::parse($this->month)->endOfMonth();
            $dates = $start->range($end)->toArray();
        }
        
        $employees = User::whereIn('group', ['user', 'student'])
            ->when($this->search, function (Builder $q) {
                return $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%');
            })
            ->when($this->division, fn (Builder $q) => $q->where('division_id', $this->division))
            ->when($this->jobTitle, fn (Builder $q) => $q->where('job_title_id', $this->jobTitle))
            ->when($this->education, fn (Builder $q) => $q->where('education_id', $this->education))
            ->paginate(20)->through(function (User $user) {
                if ($this->date) {
                    $attendances = new Collection(Cache::remember(
                        "attendance-{$user->id}-{$this->date}",
                        now()->addDay(),
                        function () use ($user) {
                            /** @var Collection<Attendance>  */
                            $attendances = Attendance::filter(
                                userId: $user->id,
                                date: $this->date,
                            )->get();

                            return $attendances->map(
                                function (Attendance $v) {
                                    $v->setAttribute('coordinates', $v->lat_lng);
                                    $v->setAttribute('lat', $v->latitude);
                                    $v->setAttribute('lng', $v->longitude);
                                    if ($v->attachment) {
                                        $v->setAttribute('attachment', $v->attachment_url);
                                    }
                                    if ($v->shift) {
                                        $v->setAttribute('shift', $v->shift->name);
                                    }
                                    // Add face recognition data
                                    if ($v->face_photo_path) {
                                        $v->setAttribute('face_photo_url', \Storage::url($v->face_photo_path));
                                    }
                                    if ($v->face_photo_out_path) {
                                        $v->setAttribute('face_photo_out_url', \Storage::url($v->face_photo_out_path));
                                    }
                                    return $v->getAttributes();
                                }
                            )->toArray();
                        }
                    ) ?? []);
                } else if ($this->week) {
                    $attendances = new Collection(Cache::remember(
                        "attendance-{$user->id}-{$this->week}",
                        now()->addDay(),
                        function () use ($user) {
                            /** @var Collection<Attendance>  */
                            $attendances = Attendance::filter(
                                userId: $user->id,
                                week: $this->week,
                            )->get(['id', 'status', 'date', 'latitude', 'longitude', 'attachment', 'note', 'face_photo_path', 'face_similarity_score', 'validation_method']);

                            return $attendances->map(
                                function (Attendance $v) {
                                    $v->setAttribute('coordinates', $v->lat_lng);
                                    $v->setAttribute('lat', $v->latitude);
                                    $v->setAttribute('lng', $v->longitude);
                                    if ($v->attachment) {
                                        $v->setAttribute('attachment', $v->attachment_url);
                                    }
                                    if ($v->face_photo_path) {
                                        $v->setAttribute('face_photo_url', \Storage::url($v->face_photo_path));
                                    }
                                    return $v->getAttributes();
                                }
                            )->toArray();
                        }
                    ) ?? []);
                } else if ($this->month) {
                    $my = Carbon::parse($this->month);
                    $attendances = new Collection(Cache::remember(
                        "attendance-{$user->id}-{$my->month}-{$my->year}",
                        now()->addDay(),
                        function () use ($user) {
                            /** @var Collection<Attendance>  */
                            $attendances = Attendance::filter(
                                month: $this->month,
                                userId: $user->id,
                            )->get(['id', 'status', 'date', 'latitude', 'longitude', 'attachment', 'note', 'face_photo_path', 'face_similarity_score', 'validation_method']);

                            return $attendances->map(
                                function (Attendance $v) {
                                    $v->setAttribute('coordinates', $v->lat_lng);
                                    $v->setAttribute('lat', $v->latitude);
                                    $v->setAttribute('lng', $v->longitude);
                                    if ($v->attachment) {
                                        $v->setAttribute('attachment', $v->attachment_url);
                                    }
                                    if ($v->face_photo_path) {
                                        $v->setAttribute('face_photo_url', \Storage::url($v->face_photo_path));
                                    }
                                    return $v->getAttributes();
                                }
                            )->toArray();
                        }
                    ) ?? []);
                } else {
                    /** @var Collection */
                    $attendances = Attendance::where('user_id', $user->id)
                        ->get(['id', 'status', 'date', 'latitude', 'longitude', 'attachment', 'note', 'face_photo_path', 'face_similarity_score', 'validation_method']);
                }
                $user->attendances = $attendances;
                return $user;
            });
            
        $schedules = Shift::all();
        
        return view('livewire.admin.attendance', [
            'employees' => $employees, 
            'dates' => $dates,
            'schedules' => $schedules
        ]);
    }
}
