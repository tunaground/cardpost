<?php

namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\Console;
use Tunacan\Bundle\Component\Content;
use Tunacan\Bundle\DataObject\CardDto;
use Tunacan\Bundle\DataObject\PostDto;
use Tunacan\Bundle\Service\CardServiceInterface;
use Tunacan\Bundle\Service\FileUploadServiceInterface;
use Tunacan\Bundle\Service\PostServiceInterface;
use Tunacan\Bundle\Service\WriteCardServiceInterface;
use Tunacan\Bundle\Service\WritePostServiceInterface;
use Tunacan\Database\DataSource;
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
    /**
     * @Inject
     * @var PostServiceInterface
     */
    private $postService;

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
        try {
            $this->writePostService->checkAbuseRequest($this->request->getPostParam('content'));
            DataSource::beginTransaction();
            $cardDto = new CardDto();
            $cardDto->setBbsUid($this->request->getPostParam('bbs_uid'));
            $cardDto->setTitle($this->request->getPostParam('title'));
            $cardDto->setPassword($this->request->getPostParam('password'));
            $cardUid = $this->writeCardService->writeCard($cardDto);
            $createdCardDto = $this->cardService->getCardDataOnlyByCardUid($cardUid);

            $postDto = new PostDto();
            $postDto->setOrder(0);
            $postDto->setCardUid($cardUid);
            $postDto->setCreateDate($createdCardDto->getOpenDate());
            $postDto->setBbsUid($this->request->getPostParam('bbs_uid'));
            $postDto->setName($this->request->getPostParam('name'));
            $postDto->setContent(new Content($this->request->getPostParam('content')));
            $postDto->setImage($this->request->getPostParam('image'));
            $postDto->setIp($this->request->getServerInfo('REMOTE_ADDR'));
            if ($this->request->getFile('image')['size'] > 0) {
                $imageName = $this->fileUploadService->putImage(
                    $this->request->getFile('image'),
                    $cardUid,
                    0
                );
                $postDto->setImage($imageName);
            }
            $this->writePostService->writePost($postDto, new Console($this->request->getPostParam('console')));
            DataSource::commit();
            $this->response->addHeader('Location: ' . $this->request->getServerInfo('HTTP_REFERER'));
        } catch (\Exception $e) {
            DataSource::rollBack();
            $this->response->addHeader('HTTP/1.1 500 Internal Server Error');
            $this->response->setBody("에러 발생");
        } finally {
            DataSource::clear();
            return $this->response;
        }
    }

    public function writePost()
    {
        try {
            $this->writePostService->checkAbuseRequest($this->request->getPostParam('content'));
            $postDto = new PostDto();
            $postDto->setOrder(
                $this->postService->getLastPostOrder($this->request->getPostParam('card_uid')) + 1
            );
            $postDto->setCardUid($this->request->getPostParam('card_uid'));
            $postDto->setBbsUid($this->request->getPostParam('bbs_uid'));
            $postDto->setName($this->request->getPostParam('name'));
            $postDto->setContent(new Content($this->request->getPostParam('content')));
            $postDto->setImage($this->request->getPostParam('image'));
            $postDto->setIp($this->request->getServerInfo('REMOTE_ADDR'));
            if ($this->request->getFile('image')['size'] > 0) {
                $imageName = $this->fileUploadService->putImage(
                    $this->request->getFile('image'),
                    $this->request->getPostParam('card_uid'),
                    $this->cardService->getCardSize($this->request->getPostParam('card_uid'))
                );
                $postDto->setImage($imageName);
            }
            $this->writePostService->writePost($postDto, new Console($this->request->getPostParam('console')));
            $this->response->addHeader('Location: ' . $this->request->getServerInfo('HTTP_REFERER'));
        } catch (\Exception $e) {
            $this->response->addHeader('HTTP/1.1 500 Internal Server Error');
            $this->response->setBody("에러 발생");
        } finally {
            return $this->response;
        }
    }
}