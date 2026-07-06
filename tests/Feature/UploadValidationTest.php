<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\UploadValidation;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class UploadValidationTest extends TestCase
{
    public function test_upload_validation_infrastructure_files_exist(): void
    {
        $this->assertFileExists(base_path('lang/ru/validation.php'));
        $this->assertFileExists(base_path('php/uploads.ini'));
        $this->assertFileExists(base_path('nginx/localhost.conf'));
        $this->assertFileExists(base_path('nginx/default.conf'));
    }

    public function test_upload_limits_are_present_in_infrastructure_files(): void
    {
        $uploadsIni = file_get_contents(base_path('php/uploads.ini'));
        $dockerCompose = file_get_contents(base_path('docker-compose.yml'));
        $localhostNginx = file_get_contents(base_path('nginx/localhost.conf'));
        $defaultNginx = file_get_contents(base_path('nginx/default.conf'));

        $this->assertStringContainsString('upload_max_filesize=100M', $uploadsIni);
        $this->assertStringContainsString('post_max_size=128M', $uploadsIni);
        $this->assertStringContainsString('memory_limit=4096M', $uploadsIni);
        $this->assertStringContainsString('max_file_uploads=50', $uploadsIni);
        $this->assertStringContainsString('./php/uploads.ini:/usr/local/etc/php/conf.d/uploads.ini', $dockerCompose);
        $this->assertStringContainsString('./nginx/localhost.conf:/etc/nginx/conf.d/default.conf', $dockerCompose);
        $this->assertStringContainsString('client_max_body_size 128m;', $localhostNginx);
        $this->assertStringContainsString('client_max_body_size 128m;', $defaultNginx);
    }

    public function test_global_uploaded_message_explains_possible_size_reason(): void
    {
        $langFile = file_get_contents(base_path('lang/ru/validation.php'));
        $message = trans('validation.uploaded', ['attribute' => 'файл']);

        $this->assertStringContainsString("'uploaded'", $langFile);
        $this->assertMatchesRegularExpression('/[А-Яа-я]/u', $message);
        $this->assertStringContainsString('превышает максимальный размер', $message);
    }

    public function test_upload_validation_messages_use_human_readable_limits(): void
    {
        $imageMessages = UploadValidation::messages(['image']);
        $importMessages = UploadValidation::messages(['file'], 'import', false);

        $this->assertSame('Изображение слишком большое. Максимальный размер: 20 МБ.', $imageMessages['image.max']);
        $this->assertSame('Файл слишком большой. Максимальный размер: 100 МБ.', $importMessages['file.max']);
    }

    public function test_editor_upload_with_large_file_returns_json_error(): void
    {
        $this->actingAsTestUser();

        $file = UploadedFile::fake()
            ->image('large.jpg')
            ->size(UploadValidation::maxKb('editor_image') + 1);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->post(route('admin.image_upload'), ['upload' => $file]);

        $response->assertStatus(422);
        $response->assertJsonPath('error.message', 'Изображение слишком большое. Максимальный размер: 5 МБ.');
    }

    public function test_post_too_large_exception_returns_json_413_for_ajax(): void
    {
        Route::post('/_test/post-too-large', fn() => throw new PostTooLargeException());

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->post('/_test/post-too-large');

        $response->assertStatus(413);
        $response->assertJsonPath('message', 'Файл или данные формы слишком большие. Максимальный размер загрузки: 128 МБ.');
        $response->assertJsonPath('errors.file.0', 'Файл или данные формы слишком большие. Максимальный размер загрузки: 128 МБ.');
    }

    public function test_import_upload_with_invalid_extension_returns_readable_error(): void
    {
        $this->actingAsTestUser();

        $file = UploadedFile::fake()->create('catalog.txt', 1, 'text/plain');

        $response = $this->from(route('admin.import.catalog'))->post(route('admin.import.catalog.upload'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.import.catalog'));
        $response->assertSessionHasErrors('file');

        $message = session('errors')->get('file')[0] ?? '';
        $this->assertStringContainsString('xlsx', $message);
        $this->assertStringContainsString('csv', $message);
    }

    private function actingAsTestUser(): void
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'Test Admin';
        $user->email = 'admin@example.test';

        $this->actingAs($user);
    }
}
