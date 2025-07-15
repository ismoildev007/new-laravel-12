<?php

namespace Modules\FileManager\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Modules\FileManager\App\DTO\UploadFileDto;

interface FileManagerInterface
{
    public function upload(Request $request);

    public function storeFile(UploadedFile $file);
    public function generatePath(UploadedFile $file): UploadFileDto;
}
