@extends('layouts.app')
<header class="header"  style="background-color:#1b2c5d; padding:30px; text-align:center;">
        @yield('header')
        <h1 style="color:white;">Frequently Asked Questions</h1>
    </div>

    </header>

@section('content')
<div class="container ">
    <div class="row justify-content-center">
        <div class="col-md-15">

         <!-- Display success message -->
         @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
    <!-- Featured FAQs -->
    <div class="row">
    @foreach($faqSubjects as $faq)
                <div class="col-md-4 mb-5">
                    <a href="{{ route('faq.show', $faq->id) }}" class="text-decoration-none">
                        <div class="card text-center" style="height: 250px;">
                            <div class="card-body">
                                <i class="fas fa-question-circle fa-3x mb-3" style="color: #1b2c5d;"></i>
                                <h5 class="card-title" style="color: #1b2c5d;">{{ $faq->title }}</h5>
                                <p class="card-text" style="color: #1b2c5d;">{{ $faq->description }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
      
      </div>
      <div class="text-right mb-4">
                <a href="{{ route('faq.form') }}" class="btn btn-secondary">Add FAQ</a>
            </div>  <!-- Add more FAQ items as needed -->
    </div>
</div>
</div>



<!-- Script for FAQ Search -->
<script>
    
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseover', function() {
            this.style.backgroundColor = '#1b2c5d';
            this.querySelector('.fas').style.color = 'white';
            this.querySelector('.card-title').style.color = 'white';
            this.querySelector('.card-text').style.color = 'white';
        });
        card.addEventListener('mouseout', function() {
            this.style.backgroundColor = 'white';
            this.querySelector('.fas').style.color = '#1b2c5d';
            this.querySelector('.card-title').style.color = '#1b2c5d';
            this.querySelector('.card-text').style.color = '#1b2c5d';
        });
    });
</script>
@endsection