<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\StorageInterface;

class FileUploadService
{
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function putImage(array $file, int $cardUid, int $postUid): string
    {
        $imageKey = $this->makeImageKey($file['name'], $cardUid, $postUid);
        $this->storage->put($imageKey, $file);
        return $imageKey;
    }

    private function makeImageKey(string $fileName, int $cardUid, int $postUid)
    {
        return sprintf(
            'image/%s/%s-%s',
            $cardUid,
            $postUid,
            $fileName
        );
    }
}