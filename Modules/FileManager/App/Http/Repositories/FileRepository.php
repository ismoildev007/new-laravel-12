<?php

namespace Modules\FileManager\App\Http\Repositories;

use App\Helpers\Roles;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Modules\FileManager\App\DTO\UploadFileDto;
use Modules\FileManager\App\Models\File;
use Modules\FileManager\Interfaces\FileManagerInterface;
use Throwable;

class FileRepository implements FileManagerInterface
{
    use ApiResponseTrait;

    /**
     * @throws Throwable
     */
    public function upload(Request $request): array
    {
        $files = $request->file('files');
        $uploaded = [];

        if (is_array($files)) {
            foreach ($files as $file) {
                $_file = $this->storeFile($file);
                if ($_file instanceof File) {
                    $uploaded[] = $_file;
                }
            }
        } elseif ($files instanceof UploadedFile) {
            $_file = $this->storeFile($files);
            if ($_file instanceof File) {
                $uploaded[] = $_file;
            }
        }

        return $uploaded;
    }


    public function storeFile(UploadedFile $file)
    {
        DB::beginTransaction();
        try {
            $dto = $this->generatePath($file);
            $fullPath = storage_path('app/public/' . $dto->file_folder);


            Storage::disk('public')->putFileAs($dto->file_folder, $file, $dto->file);

            $savedFile = File::create($dto->toArray());

            $this->createThumbnails($savedFile);
            DB::commit();
            return $savedFile;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw $e;
        }
    }


    public function generatePath(UploadedFile $file): UploadFileDto
    {
        $dto = new UploadFileDto();
        $dto->title = $file->getClientOriginalName();
        $dto->size = $file->getSize();
        $dto->ext = $file->getClientOriginalExtension();
        $dto->user_id = auth()->id();
        $dto->description = $file->getClientOriginalName();
        $dto->domain = config('app.url') . '/storage';

        $timestamp = now();
        $folders = [
            $timestamp->format('Y'),
            $timestamp->format('m'),
            $timestamp->format('d'),
            $timestamp->format('H'),
            $timestamp->format('i'),
        ];

        $dto->folder = implode('/', $folders) . '/';
        $dto->slug = Str::random(18);
        $dto->file = $dto->slug . '.' . $dto->ext;
        $dto->path = $dto->folder . $dto->file;
        $dto->file_folder = $dto->folder;

        return $dto;
    }


    private function createThumbnails(File $file): ?bool
    {
        $imageExtensions = config('filemanager.images_ext', ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
        if (!in_array(strtolower($file->ext), $imageExtensions)) {
            return null;
        }

        $thumbsImages = config('filemanager.thumbs');
        $originPath = storage_path('app/public/' . $file->folder . $file->file);

        if (!file_exists($originPath)) {
            report(new \Exception("Original file not found: " . $originPath));
            return false;
        }

        try {
            foreach ($thumbsImages as $thumb) {
                $width = $thumb['w'];
                $height = $thumb['h'] ?? null;
                $quality = $thumb['q'];
                $slug = $thumb['slug'];

                $thumbnailPath = storage_path('app/public/' . $file->folder . $file->slug . '_' . $slug . '.' . $file->ext);

                if (strtolower($file->ext) === 'svg') {
                    copy($originPath, $thumbnailPath);
                } else {
                    $img = Image::make($originPath);

                    if ($height) {
                        $img->fit($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    } else {
                        $img->resize($width, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    }

                    $img->save($thumbnailPath, $quality);

                    $img->destroy();
                }

                \Log::info("Thumbnail created: " . $thumbnailPath);
            }
        } catch (\Throwable $e) {
            report($e);
            \Log::error("Thumbnail creation failed: " . $e->getMessage());
            return false;
        }

        return true;
    }


    private function debugImageInfo(string $path): array
    {
        if (!file_exists($path)) {
            return ['error' => 'File not found'];
        }

        $info = getimagesize($path);
        return [
            'path' => $path,
            'exists' => file_exists($path),
            'size' => filesize($path),
            'dimensions' => $info ? $info[0] . 'x' . $info[1] : 'N/A',
            'mime' => $info[2] ?? 'N/A'
        ];
    }



    public function index(Request $request): JsonResponse
    {
        $query = File::query();
        $user = \Auth::guard('api')->user();
        if ($user && $user->role !== Roles::ROLE_ADMIN) {
            $query->where('user_id', $user->id);
        }
        return $this->withPagination($query, $request);
    }


    public function delete(File $file): int|bool
    {
        $this->deleteFile($file->path);
        return $file->delete();
    }

    public function deleteFile($path): void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

}
