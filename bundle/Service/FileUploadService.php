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
     * @param int $cardUID
     * @param int $postUID
     * @return string
     * @throws \Exception
     */
    public function putImage(array $file, int $cardUID, int $postUID): string
    {
        if ($file['size'] > $this->imageSizeLimit) {
            throw new \Exception("File larger than {$this->imageSizeLimit} byte.");
        }
        if (!in_array($file['type'], $this->allowedImageType, 'true')) {
            throw new \Exception('Not allowed file type.');
        }
        try {
            $imageKey = $this->makeImageKey($file['name'], $cardUID, $postUID);
            $this->storage->put($imageKey, $file);
            return $imageKey;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function makeImageKey(string $fileName, int $cardUID, int $postUID)
    {
        return sprintf(
            'image/%s/%s-%s',
            $cardUID,
            $postUID,
            $fileName
        );
    }
}