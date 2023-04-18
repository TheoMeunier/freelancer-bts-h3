<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileServiceInterface
{
    public function upload(UploadedFile $file, string $path): string;
    public function delete(string $file, string $path): void;
}
