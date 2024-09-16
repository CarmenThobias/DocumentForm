<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        // If you need to fetch FAQs from a database, do it here.
        return view('faqs.index');
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
