<?php

namespace Tunacan\Bundle\Service;

interface FileUploadServiceInterface
{
    public function putImage(array $file, int $cardUID, int $postUID): string;
}