@extends('layouts.app')
<header class="header"  style="background-color:#1b2c5d; padding:30px; text-align:center;">
        @yield('header')
        <h1 style="color:white;">HR FAQs</h1>
        <h4 style="color:white;">Human Resources related questions.</h4>
</header>

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-12 mb-4 d-flex justify-content-between align-items-center">
            <div class="search-bar" style="width: 30%;">
                <input type="text" id="faqSearch" class="form-control" placeholder="Search FAQs...">
            </div>
        
        </div>

        <!-- FAQ Panel 1 -->
        <div class="col-md-12 mb-4 d-flex">
         
            <div class="faq-panel ">
               
                <p class="panel-description">Human Resources related questions.</p>
                <!-- FAQs related to HR can be listed here -->
                <ul class="faq-list ">
                    <li><a href="#"  data-titles="HR Policy for Remote Work" data-contents="This section describes the HR policy regarding remote work. How do you go about it we we we we we we w we we we we we we we we w ee w ewwwwwwwwww ewe wew eeweeee ewwwwwwwww ee   eeeeeeeeewwwwww wwwwwwwwwwwwwwwweeeeeeeeeee" class="faq-link" style="color: #1b2c5d;">What is the HR policy for remote work?</a></li>
                    <li><a href="#" data-titles="Are staff members permitted to work for another entity while on special leave without pay (SLWOP)?, How do I apply for Special Leave?, I am a staff member on a Temporary Appointment – can I request Special Leave?" data-contents="As outlined in staff rule 5.3 (a)(i) Special Leave without Pay (SLWOP) may be granted at the request of a staff member holding a fixed-term or continuing appointment. It is usually envisaged for advanced study or research in the interest of the United Nations. to meet personal obligations such as for child-care in cases of extended illness or for other important reasons and for such period of time as the Secretary-General may prescribe. Requests for SLWOP are reviewed on their merits and approved on the basis of the interest of the Organization. If you are interested in taking SLWOP please approach your Executive Office., Except for sabbatical leave staff members are required to address a memorandum through their Chief to their Director/ASG for approval. The approved memorandum is then forwarded to the Executive Office for administrative action. Depending on the request it may be further reviewed by OHR., Staff members holding temporary appointments may be granted special leave on an exceptional basis for compelling reasons subject to approval from the Secretary-General. " class="faq-link" style="color: #1b2c5d;">Leave</a></li>
                    <li><a href="#" data-titles="HR Policy for Remote Work" data-contents="This section describes the HR policy regarding remote work. How do you go about it we we we we we we w we we we we we we we we w ee w ewwwwwwwwww ewe wew eeweeee ewwwwwwwww ee   eeeeeeeeewwwwww wwwwwwwwwwwwwwwweeeeeeeeeee" class="faq-link" style="color: #1b2c5d;">What is the HR policy for remote work?</a></li>
                    <li><a href="#" data-titles="HR Policy for Remote Work" data-contents="This section describes the HR policy regarding remote work. How do you go about it we we we we we we w we we we we we we we we w ee w ewwwwwwwwww ewe wew eeweeee ewwwwwwwww ee   eeeeeeeeewwwwww wwwwwwwwwwwwwwwweeeeeeeeeee" class="faq-link" style="color: #1b2c5d;">How do I apply for parental leave?</a></li>
                    <li><a href="#" data-titles="HR Policy for Remote Work" data-contents="This section describes the HR policy regarding remote work. How do you go about it we we we we we we w we we we we we we we we w ee w ewwwwwwwwww ewe wew eeweeee ewwwwwwwww ee   eeeeeeeeewwwwww wwwwwwwwwwwwwwwweeeeeeeeeee" class="faq-link" style="color: #1b2c5d;">What is the HR policy for remote work?</a></li>
                    <li><a href="#" data-titles="HR Policy for Remote Work" data-contents="This section describes the HR policy regarding remote work. How do you go about it we we we we we we w we we we we we we we we w ee w ewwwwwwwwww ewe wew eeweeee ewwwwwwwww ee   eeeeeeeeewwwwww wwwwwwwwwwwwwwwweeeeeeeeeee" class="faq-link" style="color: #1b2c5d;">How do I apply for parental leave?</a></li>
                    <!-- Add more FAQ links here -->
                </ul>
            </div>
       

        <!-- Right-Aligned Paper-Looking Card -->
        <div class="col-md-7 mb-1 ml-auto" style="width: 50%;">
            <div class="paper-card" style="
                background-color: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border: 1px solid #e0e0e0;
                
            ">
                <h4 style="margin-top: 0; color: #1b2c5d;">Additional Information</h4>
                <p style="color: #333;">This section provides extra details related to HR FAQs or additional resources.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="/faq" class="btn btn-light" style="background-color:#f8f9fa; border:1px solid #ddd; color:#1b2c5d;">
                Back
            </a>
@endsection

@section('scripts')
<script>
    // Script for FAQ Search
    document.getElementById('faqSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let panels = document.getElementsByClassName('faq-panel');
        
        Array.from(panels).forEach(panel => {
            let titles = panel.querySelectorAll('.panel-title, .panel-description, .faq-list a');
            let showPanel = Array.from(titles).some(title => title.innerText.toUpperCase().indexOf(filter) > -1);
            panel.style.display = showPanel ? "" : "none";
        });
    });

    // Script to Change Card Content Based on Link Click
    const faqLinks = document.querySelectorAll('.faq-link');
    const cardTitle = document.getElementById('cardTitle');
    const cardContent = document.getElementById('cardContent');

    document.querySelectorAll('.faq-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const titles = this.getAttribute('data-titles').split(',');
        const contents = this.getAttribute('data-contents').split(',');
        
        let contentHtml = '';
        for (let i = 0; i < titles.length; i++) {
            contentHtml += `<h4 style="color: #1b2c5d;">${titles[i]}</h4>`;
            contentHtml += `<p style="color: #333;">${contents[i]}</p>`;
        }
        
        document.querySelector('.paper-card').innerHTML = contentHtml;
    });
});
</script>

@endsection
