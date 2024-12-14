<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TranslationRequest;
use App\Models\LaraLanguage;
use App\Models\LaraTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class LanguageTranslationController extends Controller
{
    public function index(Request $request, $language)
    {
        $lang = LaraLanguage::where('code', $language)->first();

        $query = LaraTranslation::query();

        $query->when($request->search, function ($q) use ($request) {
            $q->where('key', 'like', '%' . $request->search . '%')
                ->orWhere('value', 'like', '%' . $request->search . '%');
        });

        $translations = $query->where('locale', $language)->paginate(25);

        return view('languages.translations.index', compact('translations', 'lang'));
    }


    public function create(Request $request, $language)
    {
        return view('languages.translations.create', compact('language'));
    }

    public function store(TranslationRequest $request, $language)
    {
        $lang = LaraLanguage::where('code', $language)->first();

        if(!empty($lang)) {
            $translation = new LaraTranslation();
            $translation->key = $request->key;
            $translation->value = $request->value;
            $translation->locale = $language;
            $translation->group = 'custom';
            $translation->lara_language_id = $lang->id;

            if ($translation->save()) {
                return redirect()
                    ->back()
                    ->with('success', __('translation.translation_added'));
            }
        }
        return redirect()
            ->back()
            ->with('error', __('translation.translation_add_fail'));
    }

    public function update($id, $value)
    {
        $translation = LaraTranslation::where('id', $id)->first();
        $translation->value = $value;

        if ($translation->save()) {
         return response()->json(['success' => true, 'message' => __('translation.translation_updated')]);
        }
        return response()->json(['success' => false,  'message' => __('translation.translation_update_fail')]);
    }

    public function languagePublish()
    {
        $exists = LaraTranslation::count();
        if ($exists) {
            Artisan::call('translations:publish');
            return redirect()->back()->with('success', __('translation.translation_successfully_published'));
        }
        return redirect()->back()->with('error', __('translation.translation_publish_fail'));

    }

    public function languageImport()
    {
        Artisan::call('translations:import');

        return redirect()->back()->with('success', __('translation.translation_successfully_imported'));
    }
}

