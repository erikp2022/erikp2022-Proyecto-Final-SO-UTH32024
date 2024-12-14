<?php

namespace App\Console\Commands;

use App\Models\LaraLanguage;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportLangTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $folderPath = lang_path();
//        $entries = scandir($folderPath);
        $entries = array_diff(scandir($folderPath), array('..', '.','.DS_Store'));
//        dd($entries);
        $languages = array_filter($entries, function($entry) use ($folderPath) {
            $fullPath = $folderPath . '/' . $entry;
            return is_dir($fullPath) && !in_array($entry, ['.', '..']);
        });
//        dd($languages);
        DB::table('lara_translations')->truncate();

        foreach ($languages as $locale) {
            $path = lang_path().'/'.$locale;

            if (File::exists($path)) {
                $files = File::files($path);

                foreach ($files as $file) {
                    $key = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $translations = include $file->getPathname();

                    $flattenedTranslations = Arr::dot($translations);

                    foreach ($flattenedTranslations as $translationKey => $translationValue) {
                        /*if (is_array($translationValue) && !empty($translationValue)) {
                            $translationValue = json_encode($translationValue);
                        }*/
                        $laraLanguage = LaraLanguage::where('code', $locale)->first();

                        DB::table('lara_translations')->insert([
                            'lara_language_id' => $laraLanguage->id,
                            'key' => $translationKey,
                            'locale' => $locale,
                            'group' => $key,
                            'value' => is_array($translationValue) ? '' : $translationValue,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    private function hasEmptyArray($array)
    {
        foreach ($array as $value) {
            if (is_array($value) && empty($value)) {
                return true;
            }
        }
        return false;
    }
}
