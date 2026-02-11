<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AttendancesExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\AttendancesImport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{
    public function users()
    {
        return view('admin.import-export.users');
    }

    public function attendances()
    {
        return view('admin.import-export.attendances');
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Users imported successfully.');
    }

    public function importAttendances(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new AttendancesImport, $request->file('file'));

        return redirect()->back()->with('success', 'Attendances imported successfully.');
    }

    public function exportUsers()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function exportAttendances()
    {
        return Excel::download(new AttendancesExport, 'attendances.xlsx');
    }

    public function userTemplate()
    {
        // Simple template with just headers
        return Excel::download(new UsersExport(['template_mode']), 'template_users.xlsx');
    }

    public function attendanceTemplate()
    {
        // Simple template with just headers
        return Excel::download(new AttendancesExport('template_mode'), 'template_attendances.xlsx');
    }
}
