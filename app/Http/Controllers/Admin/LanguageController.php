<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaraLanguage;
use App\Models\LaraTranslation;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    private $translation;

    public function __construct(LaraTranslation $translation)
    {
        $this->translation = $translation;
    }

    public function index(Request $request)
    {
        $languages = $this->translation->groupBy('locale')->paginate();

        return view('languages.index', compact('languages'));
    }

    public function create()
    {
        $languages = LaraLanguage::all();

        return view('languages.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'language_name' => 'required'
        ]);

        $language = LaraLanguage::find($request->language_name);

        $oldTranslations = LaraTranslation::where('locale', 'en')->get();

        foreach ($oldTranslations as $oldTranslation) {
            $newTranslation = $oldTranslation->replicate();
            $newTranslation->lara_language_id = $language->id;
            $newTranslation->locale = $language->code;
            $newTranslation->value = null;
            $newTranslation->created_at = now();
            $newTranslation->save();
        }

        return redirect()
            ->route('languages.index')
            ->with('success', __('translation.language_added'));
    }
}
