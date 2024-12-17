<?php

namespace App\Support\Generators;

use Illuminate\Support\Str;

class SlugGenerator
{
    /**
     * Generate a slug from the given string.
     *
     * @param string $string
     * @return string
     */
    public static function generateSlug(string $string): string
    {
        // Transliterate the string into Latin characters
        $transliterated = self::transliterateIntoLatin($string);

        // Remove unwanted characters
        $transliterated = preg_replace('~[^-\w]+~', '', $transliterated);

        // Remove duplicate dividers and trim
        $slug = trim(preg_replace('~-+~', '-', $transliterated), '-');

        return strtolower($slug);
    }

    /**
     * Generate a unique slug for a given model.
     *
     * @param string $string
     * @param string $model Fully qualified model class name
     * @param int|null $ignoreId ID of the model to ignore (useful for updates)
     * @return string
     */
    public static function generateUniqueSlug(string $string, string $model, ?int $ignoreId = null): string
    {
        $slug = self::generateSlug($string);

        $originalSlug = $slug;
        $counter = 1;

        // Ensure uniqueness
        while ($model::where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Transliterate a string into Latin characters.
     *
     * @param string $string
     * @return string
     */
    private static function transliterateIntoLatin(string $string): string
    {
        $search = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
            'ӣ', 'ӯ', 'ҳ', 'қ', 'ҷ', 'ғ', 'Ғ', 'Ӣ', 'Ӯ', 'Ҳ', 'Қ', 'Ҷ',
            ' ', '_'
        ];

        $replace = [
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'shb', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'shb', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'i', 'u', 'h', 'q', 'j', 'g', 'g', 'i', 'u', 'h', 'q', 'j',
            '-', '-'
        ];

        // Manual transliteration
        $transliterated = str_replace($search, $replace, $string);

        // Auto transliteration (fallback for unhandled characters)
        return Str::ascii($transliterated);
    }
}
