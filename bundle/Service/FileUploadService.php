<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\StorageInterface;
use Tunacan\Bundle\DataObject\ConfigDAO;

class FileUploadService
{
    /** @var StorageInterface */
    private $storage;
    /** @var int */
    private $imageSizeLimit;
    /** @var array */
    private $imageTypeLimit;

    public function __construct(StorageInterface $storage, ConfigDAO $configDAO)
    {
        $this->storage = $storage;
        $this->imageSizeLimit = $configDAO->getConfigByKey('upload.image.limit.size');
        $this->imageTypeLimit = explode(';', $configDAO->getConfigByKey('upload.image.limit.type'));
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
        if (!in_array($file['type'], $this->imageTypeLimit, 'true')) {
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
