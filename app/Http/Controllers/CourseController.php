use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

public function downloadCDF(Request $request, $courseId)
{
    $course = Course::with(['clos', 'contents', 'assessments'])->findOrFail($courseId);
    $format = $request->get('format', 'pdf');

    if ($format === 'pdf') {
        return $this->generatePDF($course);
    } else {
        return $this->generateWord($course);
    }
}

private function generatePDF($course)
{
    $pdf = PDF::loadView('exports.cdf-pdf', [
        'course' => $course,
        'title' => 'Course Definition File (CDF)',
    ]);

    return $pdf->download("CDF_{$course->code}.pdf");
}

private function generateWord($course)
{
    $phpWord = new PhpWord();
    
    // Add title
    $section = $phpWord->addSection();
    $section->addText('Course Definition File (CDF)', ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER]);
    $section->addTextBreak(2);

    // Course Information
    $section->addText('Course Information', ['bold' => true, 'size' => 14]);
    $section->addText("Course Code: {$course->code}");
    $section->addText("Course Name: {$course->name}");
    $section->addText("Credit Hours: {$course->credit_hours}");
    $section->addText("Theory Hours: {$course->theory_hours}");
    $section->addText("Lab Hours: {$course->lab_hours}");
    $section->addText("Pre-requisites: {$course->pre_req}");
    $section->addText("Co-requisites: {$course->co_req}");
    $section->addTextBreak(1);

    // Course Introduction
    $section->addText('Course Introduction', ['bold' => true, 'size' => 14]);
    $section->addText($course->course_intro);
    $section->addTextBreak(1);

    // Course Objectives
    $section->addText('Course Objectives', ['bold' => true, 'size' => 14]);
    $section->addText($course->objectives);
    $section->addTextBreak(1);

    // Course Learning Outcomes
    $section->addText('Course Learning Outcomes (CLOs)', ['bold' => true, 'size' => 14]);
    $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
    $table->addRow();
    $table->addCell()->addText('CLO', ['bold' => true]);
    $table->addCell()->addText('Description', ['bold' => true]);
    $table->addCell()->addText('Bloom\'s Level', ['bold' => true]);
    $table->addCell()->addText('PLO', ['bold' => true]);

    foreach ($course->clos as $clo) {
        $table->addRow();
        $table->addCell()->addText($clo->clo);
        $table->addCell()->addText($clo->description);
        $table->addCell()->addText($clo->bloom);
        $table->addCell()->addText($clo->plo);
    }
    $section->addTextBreak(1);

    // Course Contents
    $section->addText('Course Contents', ['bold' => true, 'size' => 14]);
    $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
    $table->addRow();
    $table->addCell()->addText('Week #', ['bold' => true]);
    $table->addCell()->addText('Topics', ['bold' => true]);
    $table->addCell()->addText('CLOs', ['bold' => true]);

    foreach ($course->contents as $content) {
        $table->addRow();
        $table->addCell()->addText($content->week);
        $table->addCell()->addText($content->topic);
        $table->addCell()->addText($content->clos);
    }
    $section->addTextBreak(1);

    // Assessment Methods
    $section->addText('Assessment Methods', ['bold' => true, 'size' => 14]);
    $table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000']);
    $table->addRow();
    $table->addCell()->addText('Assessment Type', ['bold' => true]);
    $table->addCell()->addText('Weightage (%)', ['bold' => true]);
    $table->addCell()->addText('CLOs', ['bold' => true]);

    foreach ($course->assessments as $assessment) {
        $table->addRow();
        $table->addCell()->addText($assessment->type);
        $table->addCell()->addText($assessment->weightage);
        $table->addCell()->addText($assessment->clos);
    }
    $section->addTextBreak(1);

    // References
    $section->addText('References', ['bold' => true, 'size' => 14]);
    $section->addText($course->references);

    // Save file
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $tempFile = tempnam(sys_get_temp_dir(), 'CDF');
    $objWriter->save($tempFile);

    return response()->download($tempFile, "CDF_{$course->code}.docx")->deleteFileAfterSend(true);
} 