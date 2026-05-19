<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Schedule;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\Grade;
use App\Models\ReportCard;
use App\Models\Subject;
use App\Models\Education;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TeacherPortalController extends Controller
{
    // ==========================================
    // MATERIALS (MATERI)
    // ==========================================
    public function materials()
    {
        $materials = Material::with(['subject', 'teacher'])->orderBy('created_at', 'desc')->get();
        return view('admin.teacher.materials.index', compact('materials'));
    }

    public function createMaterial()
    {
        $subjects = Subject::all();
        return view('admin.teacher.materials.create', compact('subjects'));
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'content' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            'status' => 'required|in:active,inactive',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // MATERIALS BUG FIX #3: Validate file is actually uploaded
            if (!$file->isValid()) {
                throw new \Exception('File upload tidak valid.');
            }
            
            // Store file safely
            $filePath = $file->store('materials', 'public');
            
            // Verify the stored path is within the expected directory
            if (strpos($filePath, '..') !== false || strpos($filePath, '//') !== false) {
                Storage::disk('public')->delete($filePath);
                throw new \Exception('Path traversal terdeteksi. File tidak disimpan.');
            }
        }

        Material::create([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'teacher_id' => Auth::id(),
            'content' => $request->content,
            'file_path' => $filePath,
            'status' => $request->status,
        ]);

        return redirect()->route(Auth::user()->isTeacher ? 'teacher.materials' : 'admin.materials')->with('success', 'Materi berhasil ditambahkan!');
    }

    public function editMaterial(Material $material)
    {
        // MATERIALS BUG FIX #2: Add authorization check
        if (auth()->user()->id !== $material->teacher_id && !auth()->user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit materi ini.');
        }
        
        $subjects = Subject::all();
        return view('admin.teacher.materials.edit', compact('material', 'subjects'));
    }

    public function updateMaterial(Request $request, Material $material)
    {
        // MATERIALS BUG FIX #2: Add authorization check
        if (auth()->user()->id !== $material->teacher_id && !auth()->user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah materi ini.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'content' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            'status' => 'required|in:active,inactive',
        ]);

        $filePath = $material->file_path;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // MATERIALS BUG FIX #3: Validate file is actually uploaded
            if (!$file->isValid()) {
                throw new \Exception('File upload tidak valid.');
            }
            
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            
            // Store file safely
            $filePath = $file->store('materials', 'public');
            
            // Verify the stored path is within the expected directory
            if (strpos($filePath, '..') !== false || strpos($filePath, '//') !== false) {
                Storage::disk('public')->delete($filePath);
                throw new \Exception('Path traversal terdeteksi. File tidak disimpan.');
            }
        }

        $material->update([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'content' => $request->content,
            'file_path' => $filePath,
            'status' => $request->status,
        ]);

        return redirect()->route(Auth::user()->isTeacher ? 'teacher.materials' : 'admin.materials')->with('success', 'Materi berhasil diperbarui!');
    }

    public function deleteMaterial(Material $material)
    {
        // MATERIALS BUG FIX #1: Add authorization check
        if (auth()->user()->id !== $material->teacher_id && !auth()->user()->isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus materi ini.');
        }
        
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();
        return redirect()->route(Auth::user()->isTeacher ? 'teacher.materials' : 'admin.materials')->with('success', 'Materi berhasil dihapus!');
    }

    // ==========================================
    // SCHEDULES (JADWAL)
    // ==========================================
    public function schedules()
    {
        $schedules = Schedule::with(['subject', 'teacher', 'education'])->orderBy('day')->get();
        return view('admin.teacher.schedules.index', compact('schedules'));
    }

    public function createSchedule()
    {
        $subjects = Subject::all();
        $educations = Education::all();
        $teachers = User::where('group', 'teacher')->orWhere('group', 'admin')->get();
        return view('admin.teacher.schedules.create', compact('subjects', 'educations', 'teachers'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'education_id' => 'required|exists:educations,id',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'nullable|string|max:50',
        ]);

        Schedule::create($request->all());

        return redirect()->route(Auth::user()->isTeacher ? 'teacher.schedules' : 'admin.schedules')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function editSchedule(Schedule $schedule)
    {
        $subjects = Subject::all();
        $educations = Education::all();
        $teachers = User::where('group', 'teacher')->orWhere('group', 'admin')->get();
        return view('admin.teacher.schedules.edit', compact('schedule', 'subjects', 'educations', 'teachers'));
    }

    public function updateSchedule(Request $request, Schedule $schedule)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'education_id' => 'required|exists:educations,id',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'nullable|string|max:50',
        ]);

        $schedule->update($request->all());

        return redirect()->route(Auth::user()->isTeacher ? 'teacher.schedules' : 'admin.schedules')->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function deleteSchedule(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route(Auth::user()->isTeacher ? 'teacher.schedules' : 'admin.schedules')->with('success', 'Jadwal berhasil dihapus!');
    }

    // ==========================================
    // EXAMS (CBT EXAMS)
    // ==========================================
    public function exams()
    {
        $exams = Exam::with(['subject', 'teacher', 'questions'])->orderBy('created_at', 'desc')->get();
        return view('admin.teacher.exams.index', compact('exams'));
    }

    public function createExam()
    {
        $subjects = Subject::all();
        return view('admin.teacher.exams.create', compact('subjects'));
    }

    public function storeExam(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration' => 'required|integer|min:1',
        ]);

        Exam::create([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'teacher_id' => Auth::id(),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration' => $request->duration,
        ]);

        return redirect()->route(Auth::user()->isTeacher ? 'teacher.exams' : 'admin.exams')->with('success', 'Ujian CBT berhasil ditambahkan!');
    }

    public function showExam(Exam $exam)
    {
        $exam->load('questions');
        return view('admin.teacher.exams.show', compact('exam'));
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'option_e' => 'nullable|string',
            'correct_option' => 'required|in:A,B,C,D,E',
        ]);

        ExamQuestion::create([
            'exam_id' => $exam->id,
            'question_text' => $request->question_text,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'option_e' => $request->option_e,
            'correct_option' => $request->correct_option,
        ]);

        return redirect()->back()->with('success', 'Soal berhasil ditambahkan!');
    }

    public function deleteQuestion(Exam $exam, ExamQuestion $question)
    {
        $question->delete();
        return redirect()->back()->with('success', 'Soal berhasil dihapus!');
    }

    public function examResults(Exam $exam)
    {
        $results = ExamResult::with('user')->where('exam_id', $exam->id)->orderBy('score', 'desc')->get();
        return view('admin.teacher.exams.results', compact('exam', 'results'));
    }

    public function deleteExam(Exam $exam)
    {
        $exam->delete();
        return redirect()->route(Auth::user()->isTeacher ? 'teacher.exams' : 'admin.exams')->with('success', 'Ujian CBT berhasil dihapus!');
    }

    // ==========================================
    // GRADES (INPUT NILAI)
    // ==========================================
    public function grades(Request $request)
    {
        $educations = Education::all();
        $subjects = Subject::all();

        $selected_education = $request->input('education_id');
        $selected_subject = $request->input('subject_id');

        $students = [];
        $grades = [];

        if ($selected_education && $selected_subject) {
            $students = User::where('group', 'student')
                ->where('education_id', $selected_education)
                ->get();
            
            $grades = Grade::where('subject_id', $selected_subject)
                ->whereIn('user_id', $students->pluck('id'))
                ->get()
                ->keyBy('user_id');
        }

        return view('admin.teacher.grades.index', compact('educations', 'subjects', 'students', 'grades', 'selected_education', 'selected_subject'));
    }

    public function storeGrades(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grades' => 'required|array',
            'grades.*' => 'nullable|numeric|min:0|max:100',
            'semester' => 'required|integer|in:1,2',
            'academic_year' => 'required|string',
        ]);

        foreach ($request->grades as $studentId => $score) {
            if ($score !== null) {
                Grade::updateOrCreate([
                    'user_id' => $studentId,
                    'subject_id' => $request->subject_id,
                    'semester' => $request->semester,
                    'academic_year' => $request->academic_year,
                ], [
                    'teacher_id' => Auth::id(),
                    'score' => $score,
                    'type' => 'assignment',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Nilai berhasil disimpan!');
    }

    // ==========================================
    // REPORT CARDS (RAPORT SEMESTERAN)
    // ==========================================
    public function reportCards(Request $request)
    {
        $educations = Education::all();
        $selected_education = $request->input('education_id');

        $students = [];
        if ($selected_education) {
            $students = User::where('group', 'student')
                ->where('education_id', $selected_education)
                ->with(['education'])
                ->get();
        }

        return view('admin.teacher.report-cards.index', compact('educations', 'students', 'selected_education'));
    }

    public function showStudentReport(User $student, Request $request)
    {
        $semester = $request->input('semester', 1);
        $academic_year = $request->input('academic_year', '2023/2024');

        $grades = Grade::with('subject')
            ->where('user_id', $student->id)
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->get();

        $reportCard = ReportCard::where('student_id', $student->id)
            ->where('semester', $semester)
            ->where('academic_year', $academic_year)
            ->first();

        return view('admin.teacher.report-cards.show', compact('student', 'grades', 'reportCard', 'semester', 'academic_year'));
    }

    public function storeReportCard(Request $request, User $student)
    {
        $request->validate([
            'semester' => 'required|integer|in:1,2',
            'academic_year' => 'required|string',
            'total_score' => 'required|numeric',
            'average_score' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        ReportCard::updateOrCreate([
            'student_id' => $student->id,
            'semester' => $request->semester,
            'academic_year' => $request->academic_year,
        ], [
            'total_score' => $request->total_score,
            'average_score' => $request->average_score,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Raport berhasil disimpan!');
    }
}
