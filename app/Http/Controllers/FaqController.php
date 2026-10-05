<?php

namespace App\Http\Controllers;

use App\Models\FaqEntry;

class FaqController extends Controller
{
    public function __invoke()
    {
        $faqs = FaqEntry::where('published', true)->orderBy('position')->orderBy('id')->get();

        return view('publication.faq', compact('faqs'));
    }
}
