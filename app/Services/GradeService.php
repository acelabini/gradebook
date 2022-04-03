<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class GradeService
{
    const HOMEWORK_AVERAGE = 40;

    const EXAM_AVERAGE = 60;

    protected int $year;

    protected string $quarter;

    protected array $grades;

    protected array $students;

    public function init(string $grades) : array
    {
        $this->grades = explode("\n", $grades);
        if (empty($grades)) {
            throw new \InvalidArgumentException('Invalid input.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->parseQuarter()->parseGrades();
    }

    private function parseGrades() : array
    {
        $data = [];
        foreach ($this->grades as $grade) {
            $lines = explode(" ", $grade);
            $student = '';
            foreach ($lines as $line) {
                if (!is_numeric($line) && !in_array(strtoupper($line), ['H', 'T'])) {
                    $student .= " {$line}";
                    continue;
                }
                if (in_array(strtoupper($line), ['H', 'T'])) {
                    $key = strtoupper($line);
                    continue;
                }
                if (!isset($key)) continue;

                $student = trim($student);
                $data[$student][$key][] = $line;
            }
            if ($student) {
                // Remove the lowest value from homework array
                unset($data[$student]['H'][array_search(min($data[$student]['H']), $data[$student]['H'])]);
                $this->createGrades($student, $data[$student]['H'], $data[$student]['T']);
            }
        }

        return $this->students;
    }

    private function createGrades(string $student, array $homeworks, array $exams) : void
    {
        $average = $this->computeGrade($homeworks, $exams);
        $studentRecord = Student::firstOrCreate([
            'name' => $student
        ]);
        // Create student grade record or update if year and quarter exist
        $studentRecord->grades()->updateOrCreate([
            'quarter' => $this->quarter,
            'year' => $this->year,
        ], [
            'homeworks' => $homeworks,
            'exams' => $exams,
            'average' => $average,
        ]);

        $this->students[] = $studentRecord;
    }

    private function computeGrade(array $homeworks, array $exams) : float
    {
        $homeworkAve = ((self::HOMEWORK_AVERAGE / 100) * array_sum($homeworks)) / count($homeworks);
        $examAve = ((self::EXAM_AVERAGE / 100) * array_sum($exams)) / count($exams);

        // Round to nearest tenth
        return round($homeworkAve + $examAve, 1);
    }

    private function parseQuarter() : self
    {
        list ($quarter, $year) = explode(",", Arr::first($this->grades) ?: []);

        // Get only integer from string quarter
        $this->quarter = (int) preg_replace('/[^0-9]/', '', $quarter);
        // Remove spaces from year
        $this->year = trim($year);

        // Validate if quarter is valid
        if (!in_array($this->quarter, range(1,4))) {
            throw new \InvalidArgumentException(
                'Invalid Quarter',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        unset($this->grades[0]);

        return $this;
    }
}
