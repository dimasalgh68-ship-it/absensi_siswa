<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm as JetstreamUpdateProfileInformationForm;

class UpdateProfileInformationForm extends JetstreamUpdateProfileInformationForm
{
    /**
     * Update the user's profile information.
     *
     * @param  \Laravel\Fortify\Contracts\UpdatesUserProfileInformation  $updater
     * @return void
     */
    public function updateProfileInformation(UpdatesUserProfileInformation $updater)
    {
        $this->resetErrorBag();

        // Validate all fields including shift_id
        $validated = Validator::make($this->state, [
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'education_id' => ['nullable', 'exists:educations,id'],
            'job_title_id' => ['nullable', 'exists:job_titles,id'],
            'shift_id' => ['nullable', 'exists:shifts,id'],
        ])->validate();

        // Validate photo separately with size limit
        if (isset($this->photo)) {
            $photoValidation = Validator::make(['photo' => $this->photo], [
                'photo' => ['nullable', 'image', 'max:2048'], // 2MB = 2048KB
            ], [
                'photo.max' => 'Ukuran foto maksimal 2MB.',
                'photo.image' => 'File harus berupa gambar (JPG, PNG, dll).',
            ]);

            if ($photoValidation->fails()) {
                $this->addError('photo', $photoValidation->errors()->first('photo'));
                return;
            }

            $this->user->updateProfilePhoto($this->photo);
        }

        // Update all user information including shift_id
        $this->user->forceFill([
            'name' => $this->state['name'],
            'nisn' => $this->state['nisn'],
            'email' => $this->state['email'],
            'phone' => $this->state['phone'],
            'gender' => $this->state['gender'],
            'address' => $this->state['address'],
            'city' => $this->state['city'],
            'birth_date' => $this->state['birth_date'] ?? null,
            'birth_place' => $this->state['birth_place'] ?? null,
            'division_id' => $this->state['division_id'] ?? null,
            'education_id' => $this->state['education_id'] ?? null,
            'job_title_id' => $this->state['job_title_id'] ?? null,
            'shift_id' => $this->state['shift_id'] ?? null,
        ])->save();

        $this->dispatch('saved');
        $this->dispatch('refresh-navigation-menu');
    }

    /**
     * Delete user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        $this->user->deleteProfilePhoto();

        $this->dispatch('refresh-navigation-menu');
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return $this->user;
    }

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount()
    {
        $this->state = [
            'name' => $this->user->name,
            'nisn' => $this->user->nisn,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'gender' => $this->user->gender,
            'address' => $this->user->address,
            'city' => $this->user->city,
            'birth_date' => $this->user->birth_date,
            'birth_place' => $this->user->birth_place,
            'division_id' => $this->user->division_id,
            'education_id' => $this->user->education_id,
            'job_title_id' => $this->user->job_title_id,
            'shift_id' => $this->user->shift_id,
        ];
    }
}
