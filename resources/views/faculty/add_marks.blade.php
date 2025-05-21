@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add Marks</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('faculty.marks.store') }}">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <input type="hidden" name="section_id" value="{{ $section->id }}">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="assessment_type">Assessment Type</label>
                                    <select class="form-control" id="assessment_type" name="assessment_type" required>
                                        <option value="">Select Type</option>
                                        <option value="quiz">Quiz</option>
                                        <option value="assignment">Assignment</option>
                                        <option value="midterm">Midterm</option>
                                        <option value="final">Final</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="marks">Total Marks</label>
                                    <input type="number" class="form-control" id="marks" name="marks" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Questions</label>
                                    <div id="questions-container">
                                        <div class="question-card card mb-3">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Question Title</label>
                                                            <input type="text" class="form-control" name="questions[0][title]" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>CLOs</label>
                                                            <select class="form-control selectpicker" name="questions[0][clos][]" multiple data-live-search="true" required>
                                                                @foreach($course->clos as $clo)
                                                                    <option value="{{ $clo->id }}">CLO {{ $clo->clo_number }}: {{ $clo->description }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Total Marks</label>
                                                            <input type="number" class="form-control" name="questions[0][total_marks]" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Obtained Marks</label>
                                                            <input type="number" class="form-control" name="questions[0][obtained_marks]" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm remove-question" style="display: none;">
                                                    <i class="fas fa-trash"></i> Remove Question
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success" id="add-question">
                                        <i class="fas fa-plus"></i> Add Question
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Total Marks</th>
                                                <th>Obtained Marks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($students as $student)
                                            <tr>
                                                <td>{{ $student->name }}</td>
                                                <td class="total-marks">0</td>
                                                <td class="obtained-marks">0</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Save Marks</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize Bootstrap Select
    $('.selectpicker').selectpicker({
        noneSelectedText: 'Select CLOs',
        noneResultsText: 'No CLOs found',
        selectAllText: 'Select All',
        deselectAllText: 'Deselect All',
        liveSearchPlaceholder: 'Search CLOs...',
        size: 5
    });

    // Add Question
    $('#add-question').click(function() {
        const questionCount = $('.question-card').length;
        const newQuestion = `
            <div class="question-card card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Question Title</label>
                                <input type="text" class="form-control" name="questions[${questionCount}][title]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>CLOs</label>
                                <select class="form-control selectpicker" name="questions[${questionCount}][clos][]" multiple data-live-search="true" required>
                                    @foreach($course->clos as $clo)
                                        <option value="{{ $clo->id }}">CLO {{ $clo->clo_number }}: {{ $clo->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Total Marks</label>
                                <input type="number" class="form-control" name="questions[${questionCount}][total_marks]" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Obtained Marks</label>
                                <input type="number" class="form-control" name="questions[${questionCount}][obtained_marks]" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-question">
                        <i class="fas fa-trash"></i> Remove Question
                    </button>
                </div>
            </div>
        `;
        $('#questions-container').append(newQuestion);
        $('.selectpicker').selectpicker('refresh');
    });

    // Remove Question
    $(document).on('click', '.remove-question', function() {
        $(this).closest('.question-card').remove();
        updateQuestionIndices();
    });

    // Update question indices after removal
    function updateQuestionIndices() {
        $('.question-card').each(function(index) {
            $(this).find('input, select').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/questions\[\d+\]/, `questions[${index}]`));
                }
            });
        });
    }

    // Show remove button for first question if there are multiple questions
    function updateRemoveButtons() {
        const questionCount = $('.question-card').length;
        $('.remove-question').toggle(questionCount > 1);
    }

    // Update remove buttons on add/remove
    $('#add-question, .remove-question').on('click', function() {
        setTimeout(updateRemoveButtons, 100);
    });

    // Initial update of remove buttons
    updateRemoveButtons();
});
</script>
@endsection 