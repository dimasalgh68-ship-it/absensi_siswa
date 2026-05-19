<?php

namespace App\Livewire\Profile;

use App\Livewire\Forms\UserForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class UpdateTeacherProfileInformationForm extends Component
{
    use WithFileUploads;

    public UserForm $form;
    public $verificationLinkSent = false;

    #[\Livewire\Attributes\Computed]
    public function user()
    {
        return Auth::user();
    }

    public function mount(): void
    {
        $this->form->setUser(Auth::user());
    }

    public function updateProfileInformation(): void
    {
        $this->resetErrorBag();

        $this->form->validate();

        $user = Auth::user();

        // Handle photo upload
        if (isset($this->form->photo)) {
            $user->updateProfilePhoto($this->form->photo);
        }

        // Update user information
        $user->forceFill([
            'name' => $this->form->name,
            'email' => $this->form->email,
            'phone' => $this->form->phone,
            'gender' => $this->form->gender,
            'address' => $this->form->address,
            'city' => $this->form->city,
            'birth_date' => $this->form->birth_date,
            'birth_place' => $this->form->birth_place,
            'education_id' => $this->form->education_id,
            'job_title_id' => $this->form->job_title_id,
        ])->save();

        $this->dispatch('saved');
        $this->dispatch('refresh-navigation-menu');
    }

    public function sendEmailVerification(): void
    {
        Auth::user()->sendEmailVerificationNotification();

        $this->verificationLinkSent = true;
    }

    public function deleteProfilePhoto(): void
    {
        Auth::user()->deleteProfilePhoto();

        $this->dispatch('refresh-navigation-menu');
    }

    public function render()
    {
        return view('livewire.profile.update-teacher-profile-information-form');
    }
}
