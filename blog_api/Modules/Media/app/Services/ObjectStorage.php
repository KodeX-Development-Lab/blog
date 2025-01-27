<?php

namespace Modules\Media\Services;

use Illuminate\Support\Facades\Storage;

class ObjectStorage implements MediaStorage
{
    public function get($path)
    {
        return Storage::disk('s3')->get($path);
    }

    public function store($path, $file, $name = '')
    {
        if ($name) {
            $url = Storage::disk('s3')->putFileAs($path ?? 'files', $file, $name);

            return $url;
        }

        $url = Storage::disk('s3')->put($path ?? 'files', $file);

        return $url;
    }

    public static function getUrl($path)
    {
        return asset('uploads/' . $path);
    }

    public function getFileAsResponse($path)
    {
        return Storage::disk('s3')->response($path);
    }

    public function delete($path)
    {
        if (Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
        }

        return true;
    }
}