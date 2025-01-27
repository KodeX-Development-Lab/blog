<?php

namespace Modules\Media\Services;

interface MediaStorage
{
    public function get($path);
    public function store($path, $file, $name = '');
    public function delete($path);
}