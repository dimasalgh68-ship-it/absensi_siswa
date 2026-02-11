<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class UsersExport implements FromView
{
    /**
     * @param array<string> $groups
     */
    public function __construct(private array $groups = ['user'])
    {
    }

    public function view(): View
    {
        $isTemplate = in_array('template_mode', $this->groups);
        $users = $isTemplate ? collect([]) : User::whereIn('group', $this->groups)->get();

        return view('admin.import-export.export-users', [
            'users' => $users,
            'isTemplate' => $isTemplate
        ]);
    }
}
