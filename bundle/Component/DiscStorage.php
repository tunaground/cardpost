<?php
namespace Tunacan\Bundle\Component;

use Tunacan\Http\Request;

class DiscStorage implements StorageInterface
{
    /** @var Request */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get(string $key)
    {

    }

    public function put(string $key, $file)
    {
        $docRoot = $this->request->getServerInfo('DOCUMENT_ROOT');
        $uploadPath = "{$docRoot}/{$key}";
        @mkdir(dirname($uploadPath));
        move_uploaded_file($file['tmp_name'], $uploadPath);
    }
}