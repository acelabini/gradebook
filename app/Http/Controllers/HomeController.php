<?php

namespace App\Http\Controllers;

use App\Models\StudentGrade;
use App\Services\GradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Quarter 1, 2019
    John Wright H 86 55 96 78 T 82 89 93 70 74 H 93 85 80 74 76 82 62
    Susan Smith H 75 88 94 95 84 68 91 74 100 82 93 T 73 82 81 92 85
    Jane Jones T 88 94 100 82 95 H 84 66 74 98 92 85 100 95 96 42 88
    Jimmy Doe H 73 99 98 83 85 92 100 60 74 98 92 T 84 96 79 91 95
    Suzy Johnson H 65 72 78 80 82 74 76 0 85 75 76 T 74 79 70 83 78
     *
     */
    public function home()
    {
        $students = StudentGrade::with('student')
            ->orderBy('quarter', 'asc')
            ->orderBy('average', 'desc')
            ->get();

        return view('home', compact('students'));
        $grades = "";

        (new GradeService())->init($grades);
    }

    public function store(Request $request) : RedirectResponse
    {
        $grades = $request->get('grades');

        // Select grades from file if file is present
        $key = $request->hasFile('grades_file') ? 'grades_file' : 'grades';
        if ($request->hasFile('grades_file')) {
            $grades = $request->file('grades_file')->getContent();
        }

        // Throw an error if input is empty
        if (!$grades) {
            return $this->backWithErrors(
                $key,
                "Grades can't be empty."
            );
        }

        try {
            $students = (new GradeService())->init($grades);
            return back()->with('students', collect([$students]));
        } catch (\InvalidArgumentException $e) {
            return $this->backWithErrors($key, $e->getMessage());
        } catch (\Exception | \Throwable $e) {
            return $this->backWithErrors($key, 'Invalid grade content.');
        }
    }

    private function backWithErrors(string $key, string $error) : RedirectResponse
    {
        return back()->withErrors([
            $key => $error
        ]);
    }
}
