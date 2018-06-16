<?php

namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\Console;
use Tunacan\Bundle\Component\Content;
use Tunacan\Bundle\DataObject\CardDto;
use Tunacan\Bundle\DataObject\PostDto;
use Tunacan\Bundle\Service\CardServiceInterface;
use Tunacan\Bundle\Service\FileUploadServiceInterface;
use Tunacan\Bundle\Service\WriteCardServiceInterface;
use Tunacan\Bundle\Service\WritePostServiceInterface;
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
     * @var WritePostServiceInterface
     */
    private $writePostService;
    /**
     * @Inject
     * @var WriteCardServiceInterface
     */
    private $writeCardService;

    public function main()
    {
        if ($this->request->getPostParam('type') === 'card') {
            return $this->writeCard();
        } else if ($this->request->getPostParam('type') === 'post') {
            return $this->writePost();
        }
    }

    public function writeCard()
    {
        $cardDto = new CardDto();
        $cardDto->setBbsUid($this->request->getPostParam('bbs_uid'));
        $cardDto->setTitle($this->request->getPostParam('title'));
        $cardDto->setPassword($this->request->getPostParam('password'));
        $cardUid = $this->writeCardService->writeCard($cardDto);
        $createdCardDto = $this->cardService->getCardDataOnlyByCardUid($cardUid);

        $postDto = new PostDto();
        $postDto->setCardUid($cardUid);
        $postDto->setCreateDate($createdCardDto->getOpenDate());
        $postDto->setBbsUid($this->request->getPostParam('bbs_uid'));
        $postDto->setName($this->request->getPostParam('name'));
        $postDto->setContent(new Content($this->request->getPostParam('content')));
        $postDto->setImage($this->request->getPostParam('image'));
        $postDto->setIp($this->request->getServerInfo('REMOTE_ADDR'));
        if ($this->request->getFile('image')['size'] > 0) {
            $imageName = $this->putImage();
            $postDto->setImage($imageName);
        }
        $this->writePostService->writePost($postDto, new Console($this->request->getPostParam('console')));
        $this->response->addHeader('Location: ' . $this->request->getServerInfo('HTTP_REFERER'));
        return $this->response;
    }

    public function writePost()
    {
        $postDto = new PostDto();
        $postDto->setCardUid($this->request->getPostParam('card_uid'));
        $postDto->setBbsUid($this->request->getPostParam('bbs_uid'));
        $postDto->setName($this->request->getPostParam('name'));
        $postDto->setContent(new Content($this->request->getPostParam('content')));
        $postDto->setImage($this->request->getPostParam('image'));
        $postDto->setIp($this->request->getServerInfo('REMOTE_ADDR'));
        if ($this->request->getFile('image')['size'] > 0) {
            $imageName = $this->putImage();
            $postDto->setImage($imageName);
        }
        $this->writePostService->writePost($postDto, new Console($this->request->getPostParam('console')));
        $this->response->addHeader('Location: ' . $this->request->getServerInfo('HTTP_REFERER'));
        return $this->response;
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