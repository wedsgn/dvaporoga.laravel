<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class EditorImageUploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), ['upload' => 'required|file|image|max:5000']);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'message' => $validator->errors()->first('upload')
                ]
            ], 400);
        }

        $defaultImage = Image::read($request->file('upload'));
        $imageWidth = $defaultImage->width();
        $imageSizes = [];

        $filename = Str::ulid() . '.webp';
        $path = 'uploads_editors/' . $filename;
        $defaultImage = $defaultImage->toWebp(80);
        Storage::disk('public')->put($path, (string)$defaultImage);
        $urls['default'] = Storage::disk('public')->url($path);

        foreach ($imageSizes as $size) {
            if ($imageWidth < $size) {
                break;
            }

            $image = Image::read($request->file('upload'))
                ->resize($size, null, fn($constraint) => $constraint->aspectRatio())
                ->toWebp(80);

            $path = 'uploads_editors/' . Str::ulid() . "-$size.webp";
            Storage::disk('public')->put($path, (string)$image);
            $urls["$size"] = Storage::disk('public')->url($path);
        }

        $data = !empty($urls) ?
            ['fileName' => $filename,
            'uploaded' => 1,
            'url' => $urls['default']
            ] :
            ['error' => ['message' => 'Upload failed!']];
        return response()->json($data);
    }
}
