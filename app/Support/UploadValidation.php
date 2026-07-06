<?php

namespace App\Support;

class UploadValidation
{
    public static function imageRules(string $required = 'nullable', string $group = 'image'): array
    {
        return [
            $required,
            'image',
            'mimes:' . self::mimes($group),
            'max:' . self::maxKb($group),
        ];
    }

    public static function fileImageRules(string $required = 'required', string $group = 'image'): array
    {
        return [
            $required,
            'file',
            'mimes:' . self::mimes($group),
            'max:' . self::maxKb($group),
        ];
    }

    public static function importRules(string $required = 'required'): array
    {
        return [
            $required,
            'file',
            'mimes:' . self::mimes('import'),
            'max:' . self::maxKb('import'),
        ];
    }

    public static function messages(array $fields, string $group = 'image', bool $imageRule = true): array
    {
        $messages = [];

        foreach ($fields as $field) {
            $name = self::name($field);
            $messages[$field . '.uploaded'] = self::uploadFailedMessage($name, $group);
            $messages[$field . '.max'] = self::tooLargeMessage($name, $group);
            $messages[$field . '.mimes'] = self::mimesMessage($name, $group, $imageRule);

            if ($imageRule) {
                $messages[$field . '.image'] = $name . ' должно быть изображением.';
            }
        }

        return $messages;
    }

    public static function uploadFailedMessage(string $name, string $group): string
    {
        return 'Не удалось загрузить ' . mb_strtolower($name) . '. Возможно, превышен максимальный размер: ' . self::label($group) . '.';
    }

    public static function tooLargeMessage(string $name, string $group): string
    {
        $word = str_contains(mb_strtolower($name), 'файл') ? 'большой' : 'большое';

        return $name . ' слишком ' . $word . '. Максимальный размер: ' . self::label($group) . '.';
    }

    public static function processingFailedMessage(string $name = 'Изображение'): string
    {
        return $name . ' не удалось обработать. Проверьте формат и целостность файла.';
    }

    public static function postTooLargeMessage(): string
    {
        return 'Файл или данные формы слишком большие. Максимальный размер загрузки: ' . config('uploads.php.post_max_label', '128 МБ') . '.';
    }

    public static function maxKb(string $group): int
    {
        return (int) config("uploads.{$group}.max_kb");
    }

    public static function label(string $group): string
    {
        return (string) config("uploads.{$group}.label");
    }

    public static function mimes(string $group): string
    {
        return implode(',', (array) config("uploads.{$group}.mimes", []));
    }

    public static function name(string $field): string
    {
        if (preg_match('/^new_images\.\d+$/', $field)) {
            return 'Изображение';
        }

        if (preg_match('/^new_items\.\d+\.before$/', $field)) {
            return 'Изображение «до»';
        }

        if (preg_match('/^new_items\.\d+\.after$/', $field)) {
            return 'Изображение «после»';
        }

        return match ($field) {
            'image' => 'Изображение',
            'image_mob', 'image_mobile' => 'Мобильное изображение',
            'image_desktop' => 'Десктопное изображение',
            'company_image' => 'Изображение компании',
            'new_images.*' => 'Изображение',
            'new_items.*.before' => 'Изображение «до»',
            'new_items.*.after' => 'Изображение «после»',
            'product_images.*' => 'Изображение товара',
            'file', 'file_exel' => 'Файл',
            'upload' => 'Изображение',
            default => 'Файл',
        };
    }

    private static function mimesMessage(string $name, string $group, bool $imageRule): string
    {
        $formats = str_replace(',', ', ', self::mimes($group));
        $verb = str_contains(mb_strtolower($name), 'файл') ? 'должен' : 'должно';

        if ($imageRule) {
            return $name . ' должно быть изображением в формате: ' . $formats . '.';
        }

        return $name . ' ' . $verb . ' быть в формате: ' . $formats . '.';
    }
}
