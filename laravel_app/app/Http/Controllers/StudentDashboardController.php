<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    public function index(Request $request)
    {
        $student = Auth::guard('student')->user();

        $name = $student->name;
        $myCourse = $student->enrolled_course ?? 'none';
        $enrollmentStatus = $student->enrollment_status ?? 'pending';

        $courseNameToKey = [
            'AI' => 'ai',
            'Web-dev' => 'web-dev',
            'DIT' => 'dit',
            'CIT' => 'cit',
            'MsOffice' => 'msoffice',
            'Python' => 'python',
            'Digital Marketing' => 'digital-marketing',
            'Typing' => 'typing',
        ];

        if (isset($courseNameToKey[$myCourse])) {
            $myCourse = $courseNameToKey[$myCourse];
        }

        // Assigned teacher (if any)
        $assignedTeacherName = $student->teacher?->name;

        $studentProfile = $student;

        $profilePic = session('student_pic')
            ? asset('uploads/' . session('student_pic'))
            : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';

        $pendingFee = $student->total_fee - $student->paid_fee;

        $certReq = $student->certificateRequests()->latest()->first();

        return view('student.dashboard', [
            'name' => $name,
            'myCourse' => $myCourse,
            'assignedTeacherName' => $assignedTeacherName,
            'studentProfile' => $studentProfile,
            'profilePic' => $profilePic,
            'pendingFee' => $pendingFee,
            'certReq' => $certReq,
            'enrollmentStatus' => $enrollmentStatus,
        ]);
    }
}
