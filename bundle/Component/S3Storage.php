<?php
namespace Tunacan\Bundle\Component;

use Aws\S3\S3Client;

class S3Storage implements StorageInterface
{
    private $s3Client;
    private $bucket;

    public const ACL_PUBLIC_READ = 'public-read';

    public function __construct(S3Client $s3Client) {
        $this->s3Client = $s3Client;
    }

    public function setBucket(string $bucket)
    {
        $this->bucket = $bucket;
    }

    public function get(string $key) {
        return $this->s3Client->getObject([
            'Bucket' => $this->bucket,
            'Key' => $key
        ]);
    }

    public function put(string $key, $file, $acl = self::ACL_PUBLIC_READ) {
        $result = $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'SourceFile' => $file['tmp_name'],
            'ContentType' => $file['type'],
            'ACL' => $acl
        ]);
        return $result['ObjectURL'];
    }
}