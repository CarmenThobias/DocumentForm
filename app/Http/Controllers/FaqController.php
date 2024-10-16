<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FaqSubject;
use Illuminate\Support\Str;
use App\Models\FaqQuestion;



class FaqController extends Controller
{
    public function index()
    {
        // If you need to fetch FAQs from a database, do it here.
        $faqSubjects = FaqSubject::all();
        return view('faqs.index', compact('faqSubjects'));
    }

    public function create()
    {
        $faqSubjects = FaqSubject::all(); // Fetch all existing FAQ subjects
        return view('faqs.form', compact('faqSubjects')); 
    }

    public function store(Request $request)
{
    
    // Validate the incoming request
    $request->validate([
        'faq_subject_id' => 'required|exists:faq_subjects,id',
        'title' => 'required',
        'description' => 'nullable',
        'titles.*' => 'required',
        'contents.*' => 'required',
    ]);

    // Save the FAQ subject
    $faqSubject = FaqSubject::create([
        'title' => $request->title,
        'description' => $request->description,
    ]);

     // Loop through the titles and contents to save them
     foreach ($request->titles as $index => $title) {
        // Create a new FAQ question associated with the selected subject
        FaqQuestion::create([
            'faq_subject_id' => $faqSubject->id,
            'title' => $title,
            'content' => $request->contents[$key],
        ]);
    }

    return redirect()->route('faq.index')->with('success', 'FAQ subject created successfully.');
}
                    



public function show($id)
{
    // Fetch the FAQ subject using the id
    $faqSubject = FaqSubject::findOrFail($id);

    // Fetch the related FAQs for the subject
    $faqs = $faqSubject->questions;  // Assuming there's a relationship called 'questions'

    // Pass both $faqSubject and $faqs to the view
    return view('faqs.show', compact('faqSubject', 'faqs'));
}

    

    public function showSubject($subjectSlug)
{
    $faqSubject = FaqSubject::with('questions')->where('slug', $subjectSlug)->firstOrFail(); // Find the subject by slug
    return view('faqs.show', compact('faqSubject'));
}


    public function faqform()
    {
        // If you need to fetch FAQs from a database, do it here.
        return view('faqs.form');
    }
    public function showHR() {
        // Logic for HR FAQs
        return view('faqs.hr');
    }

    public function showIT() {
        // Logic for IT FAQs
        return view('faqs.it');
    }

    public function showHS() {
        // Logic for Health and Safety FAQs
        return view('faqs.hs');
    }

    public function showLN() {
        // Logic for Loans FAQs
        return view('faqs.ln');
    }

    public function showER() {
        // Logic for E-Resources FAQs
        return view('faqs.er');
    }

    public function showUT() {
        // Logic for Utilities FAQs
        return view('faqs.ut');
    }
}
