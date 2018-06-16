<?php

namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\StorageInterface;

class FileUploadService
{
    private $storage;
    /**
     * @Inject("upload.image.size.limit")
     * @var int
     */
    private $imageSizeLimit;
    /**
     * @Inject("upload.image.type.allowed")
     * @var array
     */
    private $allowedImageType;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param array $file
     * @param int $cardUid
     * @param int $postUid
     * @return string
     * @throws \Exception
     */
    public function putImage(array $file, int $cardUid, int $postUid): string
    {
        if ($file['size'] > $this->imageSizeLimit) {
            throw new \Exception("File size is larger than {$this->imageSizeLimit} byte.");
        }
        if (!in_array($file['type'], $this->allowedImageType, 'true')) {
            throw new \Exception('Not allowed file type.');
        }
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