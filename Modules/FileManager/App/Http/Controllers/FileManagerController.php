<?php

namespace Modules\FileManager\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\FileManager\App\Http\Repositories\FileRepository;
use Modules\FileManager\App\Http\Requests\StoreFileRequest;
use Modules\FileManager\App\Http\Resources\FileResource;
use Modules\FileManager\App\Models\File;

class FileManagerController extends Controller
{
    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;

    }

    public function upload(StoreFileRequest $request)
    {
        try {
            $uploadedFiles = $this->fileRepository->upload($request);
            return FileResource::collection($uploadedFiles);
        } catch (\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }

    public function adminUpload(StoreFileRequest $request)
    {
        try {
            $uploadedFiles = $this->fileRepository->upload($request);
            return FileResource::collection($uploadedFiles);
        } catch (\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }

    public function show(File $file)
    {
        try {
            return FileResource::make($file);
        } catch (\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }

    public function delete(File $file)
    {
        try {
             $this->fileRepository->delete($file);
             return $this->respondDeleted(['message' => 'File deleted successfully.']);
        } catch (\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }
}
