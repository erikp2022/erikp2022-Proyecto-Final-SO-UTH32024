<?php

namespace App\Console\Commands;

use App\Models\LaraTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish translations from the database to language files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $translations = LaraTranslation::all();

        foreach ($translations as $translation) {
            $path = lang_path("{$translation->locale}/{$translation->group}.php");
            $directory = dirname($path);

            // Ensure the directory exists
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }

            // Read existing content of the language file
            $existingTranslations = File::exists($path) ? include $path : [];

            // Add the new translation to the existing group
            $existingTranslations[$translation->key] = $translation->value;

            // Generate PHP code for the array with specific formatting
            $phpCode = '<?php' . PHP_EOL . PHP_EOL . 'return [' . PHP_EOL;
            /*foreach ($existingTranslations as $key => $value) {
                $phpCode .= '    "' . $key . '" => "' . $value . '",' . PHP_EOL;
            }*/
            foreach ($existingTranslations as $key => $value) {
                $phpCode .= '    "' . $key . '" => ' . var_export($value, true) . ',' . PHP_EOL;
            }
            $phpCode .= '];' . PHP_EOL;

            // Write the updated content to the language file
            File::put($path, $phpCode);
        }
        $this->info('Translations published successfully.');
    }

    private function varExportPretty($var): array|string|null
    {
        $export = var_export($var, true);
        // Add extra indentation
        return preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
    }
}
