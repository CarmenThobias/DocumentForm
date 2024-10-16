@extends('layouts.app')

<header class="header" style="background-color:#1b2c5d; padding:20px; text-align:center;">
    @yield('header')
    <h1 style="color:white;">Create FAQ Subject</h1>
</header>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <form action="{{ route('faq.store') }}" method="POST">
                @csrf
                <label for="faq_subject_id">Select FAQ Subject</label>
                    <select name="faq_subject_id" id="faq_subject_id" class="form-control" required>
                        <option value="">Select a subject</option>
                        @foreach($faqSubjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="linkedQuestions">
                    <h4>Linked Questions</h4>
                    <div class="linked-question">
                        <input type="text" name="titles[]" placeholder="Question Title" class="form-control" required>
                        <textarea name="contents[]" placeholder="Question Content" class="form-control" rows="2" required></textarea>
                    </div>
                    <button type="button" class="remove-question btn btn-danger btn-sm ">Remove</button>
                    
                </div>
                
                <button type="button" id="addQuestion" class="btn btn-secondary btn-sm col-md-4 mb-5">Add Linked Question</button>
                <button type="submit" class="btn btn-primary btn-sm mt-3 col-md-4 mb-5">Create FAQ Subject</button>

            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.getElementById('addQuestion').addEventListener('click', function() {
        const questionContainer = document.createElement('div');
        questionContainer.classList.add('linked-question');
        questionContainer.innerHTML = `
            <input type="text" name="titles[]" placeholder="Question Title" class="form-control" required>
            <textarea name="contents[]" placeholder="Question Content" class="form-control" rows="2" required></textarea>
            <button type="button" class="remove-question btn btn-danger btn-sm">Remove</button>
        `;
        document.getElementById('linkedQuestions').appendChild(questionContainer);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-question')) {
            e.target.parentElement.remove();
        }
    });
</script>
@endsection

@endsection
