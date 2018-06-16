<?php
namespace Tunacan\Bundle\Service;

interface FileUploadServiceInterface
{
    public function putImage(array $file, int $cardUid, int $postUid): string;
}