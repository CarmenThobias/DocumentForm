@extends('layouts.app')

<header class="header" style="background-color:#1b2c5d; padding:30px; text-align:center;">
    <h1 style="color:white;">{{ $faqSubject->title }} FAQs</h1>
    <h4 style="color:white;">{{ $faqSubject->description }}</h4>
</header>

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4 d-flex align-items-center">
            <div class="search-bar" style="width: 30%;">
                <input type="text" id="faqSearch" class="form-control" placeholder="Search FAQs...">
            </div>
            <button type="submit" class="btn btn-primary btn-m" style="background-color:#1b2c5d;">Search</button>
        </div>

        <!-- FAQ Panel -->
        <div class="col-md-12 mb-4 d-flex">
            <div class="faq-panel">
                <p class="panel-description">{{ $faqSubject->description }}</p>
                <ul class="faq-list">
                    @foreach ($faqs as $faq)
                        <li>
                            <a href="#" data-titles="{{ $faq->title }}" data-contents="{{ $faq->content }}" class="faq-link" style="color: #1b2c5d;">
                                {{ $faq->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Right-Aligned Paper-Looking Card -->
            <div class="col-md-7 mb-1 ml-auto">
                <div class="paper-card" style="background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <h4 id="cardTitle" style="color: #1b2c5d;">Additional Information</h4>
                    <p id="cardContent" style="color: #333;">Click on a question to view the details.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="/faq" class="btn btn-light" style="background-color:#f8f9fa; border:1px solid #ddd; color:#1b2c5d;">Back</a>
@endsection

@section('scripts')
<script>
    document.getElementById('faqSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let faqLinks = document.querySelectorAll('.faq-link');
        
        faqLinks.forEach(link => {
            let linkText = link.innerText.toUpperCase();
            let title = link.getAttribute('data-titles').toUpperCase();
            let content = link.getAttribute('data-contents').toUpperCase();
            
            if (linkText.includes(filter) || title.includes(filter) || content.includes(filter)) {
                link.closest('li').style.display = '';
            } else {
                link.closest('li').style.display = 'none';
            }
        });
    });

    document.querySelectorAll('.faq-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const titles = this.getAttribute('data-titles');
            const contents = this.getAttribute('data-contents');
            
            document.querySelector('.paper-card').innerHTML = `<h4 style="color: #1b2c5d;">${titles}</h4><p style="color: #333;">${contents}</p>`;
        });
    });
</script>
@endsection
