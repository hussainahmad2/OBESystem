<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\assessment;
use App\Models\assessment_clo_detail;
use App\Models\CourseRegistration;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ObeController extends Controller
{
    public function generate(Request $request)
    {
        $courseId = $request->input('course_id');
        
        $course = Course::with(['course_outcome', 'courseAllocations.faculty.user', 'course_detail'])->find($courseId);
        if (!$course) {
            return back()->with('error', 'Course not found.');
        }

        // Get course allocation (for instructor, batch, section)
        $allocation = $course->courseAllocations->first();
        $instructor = $allocation && $allocation->faculty && $allocation->faculty->user ? $allocation->faculty->user->name : 'N/A';
        $batch = $allocation ? $allocation->batch : 'N/A';
        $section = $allocation ? $allocation->section : 'N/A';
        $semester = $course->semester ?? 'N/A';

        // Get CLOs and PLOs from course_outcome
        $clos = $course->course_outcome->pluck('clo')->unique()->values()->toArray();
        $plos = $course->course_outcome->pluck('PLO')->unique()->filter()->values()->toArray();

        // Get all assessments for this course
        $assessments = assessment::with(['marks'])
            ->where('course_id', $courseId)
            ->get();
        $assignmentTitles = $assessments->where('type', 'Assignment')->pluck('assessment_title')->unique()->values()->toArray();
        $quizTitles = $assessments->where('type', 'Quiz')->pluck('assessment_title')->unique()->values()->toArray();
        $midTitles = $assessments->where('type', 'Mid')->pluck('assessment_title')->unique()->values()->toArray();
        $finalTitles = $assessments->where('type', 'Final')->pluck('assessment_title')->unique()->values()->toArray();

        // Get all students registered for this course
        $students = CourseRegistration::with('student')
            ->where('course_id', $courseId)
            ->where('status', 'approved')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // === HEADER ROWS ===
        $sheet->mergeCells('A1:Z1');
        $sheet->setCellValue('A1', 'Foundation University Islamabad, Rawalpindi Campus');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A2:Z2');
        $sheet->setCellValue('A2', 'Department of Engineering Technology');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A3:Z3');
        $sheet->setCellValue('A3', 'Course: ' . ($course->name ?? 'N/A') . '   Instructor: ' . $instructor . '   Class and Semester: ' . $batch . '-' . $section . ' / ' . $semester);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // === CLO COLORS ===
        $cloColors = ['FFF9CB', 'FFB6E1', 'B6E0FF', 'B6FFB6', 'FFD580', 'E0B6FF', 'B6FFD9', 'FFD6B6']; // Extend as needed
        $ploColors = ['FFD6B6', 'B6FFD9', 'E0B6FF', 'FFD580'];

        // === CLO HEADER (Row 4) ===
        $sheet->mergeCells('A4:C4');
        $sheet->setCellValue('A4', 'CLO No.');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $col = 'D';
        $cloSectionCols = [];
        $sections = [
            ['label' => 'Assignment', 'titles' => $assignmentTitles],
            ['label' => 'Quiz', 'titles' => $quizTitles],
            ['label' => 'Mid term Exam', 'titles' => $midTitles],
            ['label' => 'Terminal Exam', 'titles' => $finalTitles],
        ];
        foreach ($sections as $sectionIdx => $sectionData) {
            foreach ($sectionData['titles'] as $idx => $title) {
                foreach ($clos as $cloIdx => $clo) {
                    $sheet->setCellValue($col . '4', $clo);
                    $cloSectionCols[$sectionData['label']][] = $col;
                    // Color for CLO
                    $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($cloColors[$cloIdx % count($cloColors)]);
                    $col++;
                }
            }
        }
        // PLO columns
        foreach ($plos as $ploIdx => $plo) {
            $sheet->setCellValue($col . '4', $plo);
            $sheet->getStyle($col . '4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($ploColors[$ploIdx % count($ploColors)]);
            $col++;
        }

        // === MARKS CATEGORY (Row 5) ===
        $sheet->mergeCells('A5:C5');
        $sheet->setCellValue('A5', 'Marks Category');
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $col = 'D';
        foreach ($sections as $sectionIdx => $sectionData) {
            $startCol = $col;
            $numCols = count($sectionData['titles']) * count($clos);
            if ($numCols > 0) {
                $endCol = chr(ord($col) + $numCols - 1);
                $sheet->mergeCells($col . '5:' . $endCol . '5');
                $sheet->setCellValue($col . '5', $sectionData['label']);
                // Color for section
                $sectionColors = ['b8cce4', 'd7e4bc', 'fac090', 'ccc0da'];
                $sheet->getStyle($col . '5:' . $endCol . '5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($sectionColors[$sectionIdx % count($sectionColors)]);
                $col = chr(ord($col) + $numCols);
            }
        }
        // PLO columns
        $ploStartCol = $col;
        if (count($plos) > 0) {
            $endCol = chr(ord($col) + count($plos) - 1);
            $sheet->mergeCells($ploStartCol . '5:' . $endCol . '5');
            $sheet->setCellValue($ploStartCol . '5', 'PLOs');
            $sheet->getStyle($ploStartCol . '5:' . $endCol . '5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD6B6');
        }

        // === TABLE HEADER (Row 6) ===
        $headers = ['S.No.', 'Registration No', 'Name'];
        foreach ($sections as $sectionData) {
            foreach ($sectionData['titles'] as $title) {
                foreach ($clos as $clo) {
                    $headers[] = $title . ' (' . $clo . ')';
                }
            }
        }
        foreach ($plos as $plo) {
            $headers[] = $plo;
        }
        $sheet->fromArray($headers, null, 'A6');

        // === DATA ROWS ===
        $rowIndex = 7;
        $sno = 1;
        foreach ($students as $studentReg) {
            $student = $studentReg->student;
            $row = [$sno++, $student->email ?? '', $student->name ?? ''];
            // Fill marks for each assessment type and CLO
            foreach ($sections as $sectionData) {
                foreach ($sectionData['titles'] as $title) {
                    foreach ($clos as $clo) {
                        $assessment = $assessments->where('type', $sectionData['label'])->where('assessment_title', $title)->where('student_id', $student->id)->first();
                        $mark = '';
                        if ($assessment && $assessment->marks->where('clo_number', $clo)->first()) {
                            $mark = $assessment->marks->where('clo_number', $clo)->first()->obtained_marks;
                        }
                        $row[] = $mark;
                    }
                }
            }
            // PLOs (leave blank or calculate if logic is available)
            foreach ($plos as $plo) {
                $row[] = '';
            }
            $sheet->fromArray($row, null, 'A' . $rowIndex);
            $rowIndex++;
        }

        // === FORMATTING ===
        $highestCol = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:' . $highestCol . $highestRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A1:' . $highestCol . '6')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $highestCol . '6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:' . $highestCol . '6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        // Set column widths for readability
        foreach (range('A', $highestCol) as $colLetter) {
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }
        // Download
        $filename = 'OBE_Sheet_' . ($course->name ?? 'Course') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
} 