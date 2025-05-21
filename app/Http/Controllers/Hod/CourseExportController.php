<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;

class CourseExportController extends Controller
{
    public function download($id, $format)
    {
        $course = Course::with(['course_detail', 'course_outcome', 'course_content', 'course_content_point', 'practical_outcome'])->findOrFail($id);

        $courseName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $course->course_detail->first()->title ?? $course->id);

        if ($format === 'pdf') {
            // PDF export using DomPDF
            $intro = $course->course_detail->first();
            $pdf = \PDF::loadView('lecturar.hod.cdf_export', [
                'courses_detail' => $course,
                'intro' => $intro
            ]);
            return $pdf->download($courseName . '_cdf.pdf');
        } elseif ($format === 'word') {
            // Word export using PHPWord
            $intro = $course->course_detail->first();
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();

            $section->addText($intro->title ?? 'Course Title', ['bold' => true, 'size' => 18]);
            $section->addTextBreak();
            $section->addText('Course Introduction & Objectives:', ['bold' => true, 'size' => 14]);
            $section->addText($intro->intro_objectives ?? 'No introduction available.');
            $section->addTextBreak();

            $section->addText('Course Outcomes:', ['bold' => true, 'size' => 14]);
            $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
            $table->addRow();
            $table->addCell(2000)->addText('CLO', ['bold' => true]);
            $table->addCell(6000)->addText('Description', ['bold' => true]);
            $table->addCell(2000)->addText("Bloom's Level", ['bold' => true]);
            $table->addCell(1000)->addText('PLO', ['bold' => true]);
            foreach ($course->course_outcome as $outcome) {
                $table->addRow();
                $table->addCell(2000)->addText($outcome->clo);
                $table->addCell(6000)->addText($outcome->description);
                $table->addCell(2000)->addText($outcome->{"Bloom'sLevel"});
                $table->addCell(1000)->addText($outcome->PLO);
            }
            $section->addTextBreak();

            $section->addText('Course Contents:', ['bold' => true, 'size' => 14]);
            foreach ($course->course_content as $content) {
                $section->addText(($content->heading_number ? $content->heading_number . '. ' : '') . $content->heading_title, ['bold' => true]);
                foreach ($course->course_content_point->where('course_contents_id', $content->id) as $point) {
                    $section->addListItem($point->description);
                }
            }
            $section->addTextBreak();

            $section->addText('Practical Outcomes:', ['bold' => true, 'size' => 14]);
            $ptable = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
            $ptable->addRow();
            $ptable->addCell(2000)->addText('CLO', ['bold' => true]);
            $ptable->addCell(6000)->addText('Description', ['bold' => true]);
            $ptable->addCell(2000)->addText("Bloom's Level", ['bold' => true]);
            $ptable->addCell(1000)->addText('PLO', ['bold' => true]);
            if ($course->practical_outcome && $course->practical_outcome->isNotEmpty() && !empty($course->practical_outcome[0]->description)) {
                foreach ($course->practical_outcome as $practical) {
                    $ptable->addRow();
                    $ptable->addCell(2000)->addText($practical->clo);
                    $ptable->addCell(6000)->addText($practical->description);
                    $ptable->addCell(2000)->addText($practical->{"Bloom'sLevel"});
                    $ptable->addCell(1000)->addText($practical->PLO);
                }
            }

            $fileName = $courseName . '_cdf.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word');
            $phpWord->save($tempFile, 'Word2007');
            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        } else {
            abort(404);
        }
    }
} 