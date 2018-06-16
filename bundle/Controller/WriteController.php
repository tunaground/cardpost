<?php
namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\Console;
use Tunacan\Bundle\Component\Content;
use Tunacan\Bundle\DataObject\PostDto;
use Tunacan\Bundle\Service\CardServiceInterface;
use Tunacan\Bundle\Service\FileUploadServiceInterface;
use Tunacan\Bundle\Service\WriteServiceInterface;
use Tunacan\MVC\BaseController;

class WriteController extends BaseController
{
    /**
     * @Inject
     * @var CardServiceInterface
     */
    private $cardService;
    /**
     * @Inject
     * @var FileUploadServiceInterface
     */
    private $fileUploadService;
    /**
     * @Inject
     * @var WriteServiceInterface
     */
    private $writeService;

    public function writePost()
    {
        $postDto = $this->readyPost();
        if (!is_null($this->request->getFile('image'))) {
            $imageName = $this->putImage();
            $postDto->setImage($imageName);
        }
        $this->insertPost($postDto);
        $this->response->addHeader('Location: '.$this->request->getServerInfo('HTTP_REFERER'));
        return $this->response;
    }

    private function readyPost(): PostDto
    {
        $postDto = new PostDto();
        $postDto->setCardUid($this->request->getPostParam('card_uid'));
        $postDto->setBbsUid($this->request->getPostParam('bbs_uid'));
        $postDto->setName($this->request->getPostParam('name'));
        $postDto->setContent(new Content($this->request->getPostParam('content')));
        $postDto->setImage($this->request->getPostParam('image'));
        $postDto->setIp($this->request->getServerInfo('REMOTE_ADDR'));
        return $postDto;
    }

    private function insertPost(PostDto $postDto): int
    {
        return $this->writeService->writePost(
            $postDto,
            new Console($this->request->getPostParam('console'))
        );
    }

    private function putImage()
    {
        return $this->fileUploadService->putImage(
            $this->request->getFile('image'),
            $this->request->getPostParam('card_uid'),
            $this->cardService->getCardSize($this->request->getPostParam('card_uid'))
        );
    }
}