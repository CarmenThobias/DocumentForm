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
            
    
    <!-- Featured FAQs -->

    <div class="row">
        <!-- FAQ Card 1 -->
        <div class="col-md-4 mb-5">
        <a href="{{ url('/faq/hr') }}" class="text-decoration-none">
        <div class="card text-center" style="height: 250px;">
                <div class="card-body">
                <i class="fas fa-users fa-3x mb-3" style="color: #1b2c5d;"></i>
                    <h5 class="card-title" style="color: #1b2c5d;">HR</h5>
                    <p class="card-text" style="color: #1b2c5d;">Human Resources related questions.</p>
                </div>
            </div>
        </div>
        
        <!-- FAQ Card 2 -->
        <div class="col-md-4 mb-5">
        <a href="{{ url('/faq/it') }}" class="text-decoration-none">
        <div class="card text-center" style="height: 250px;">
                <div class="card-body">
                <i class="fas fa-laptop fa-3x mb-3" style="color: #1b2c5d;"></i>
                    <h5 class="card-title " style="color: #1b2c5d;">IT</h5>
                    <p class="card-text" style="color: #1b2c5d;">Information Technology related questions.</p>
                </div>
            </div>
        </div>

        <!-- FAQ Card 3 -->
        <div class="col-md-4 mb-5">
        <a href="{{ url('/faq/hs') }}" class="text-decoration-none">
        <div class="card text-center" style="height: 250px;">
                <div class="card-body">
                <i class="fas fa-shield-alt fa-3x mb-3" style="color: #1b2c5d;"></i>
                    <h5 class="card-title" style="color: #1b2c5d;">Health and Safety</h5>
                    <p class="card-text" style="color: #1b2c5d;">Occupational safety and health or occupational health and safety is a multidisciplinary field concerned with the safety, health, and welfare of people at work.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- General FAQs -->
    <div class="row">
        <div class="col-md-4 mb-5">
        <a href="{{ url('/faq/ln') }}" class="text-decoration-none">
        <div class="card text-center" style="height: 250px;">
                <div class="card-body">
                <i class="fas fa-hand-holding-usd fa-3x mb-3" style="color: #1b2c5d;"></i>
                    <h5 class="card-title" style="color: #1b2c5d;">Loans</h5>
                    <p class="card-text" style="color: #1b2c5d;">it involves the creation of a debt, which will be repaid with added interest.</p>
                </div>
        </div>
        </div>
        <div class="col-md-4 mb-5">
        <a href="{{ url('/faq/er') }}" class="text-decoration-none">
        <div class="card text-center" style="height: 250px;">
                <div class="card-body">
                <i class="fas fa-book-reader fa-3x mb-3" style="color: #1b2c5d;"></i>
                    <h5 class="card-title" style="color: #1b2c5d;">E-Resources</h5>
                    <p class="card-text" style="color: #1b2c5d;"> Digital information and materials accessed and utilized through various devices</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-5">
        <a href="{{ url('/faq/ut') }}" class="text-decoration-none">
        <div class="card text-center" style="height: 250px;">
                <div class="card-body">
                <i class="fas fa-toolbox fa-3x mb-3" style="color: #1b2c5d;"></i>
                    <h5 class="card-title" style="color: #1b2c5d;">Utilities</h5>
                    <p class="card-text" style="color: #1b2c5d;">Software programs that help configure, monitor, or maintain a computer.</p>
                </div>
            </div>
        </div>
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