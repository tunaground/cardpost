<?php

namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\Console;
use Tunacan\Bundle\Component\Content;
use Tunacan\Bundle\DataObject\CardDTO;
use Tunacan\Bundle\DataObject\PostDTO;
use Tunacan\Bundle\Service\CardServiceInterface;
use Tunacan\Bundle\Service\FileUploadServiceInterface;
use Tunacan\Bundle\Service\ManagementServiceInterface;
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
    /**
     * @Inject
     * @var ManagementServiceInterface
     */
    private $mgmtService;

    public function main()
    {
        if ($this->request->getPostParam('type') === 'card') {
            return $this->writeCard();
        } else if ($this->request->getPostParam('type') === 'post') {
            return $this->writePost();
        } else {
            $this->response->addHeader('HTTP/1.1 500 Internal Server Error');
            $this->response->addAttribute('error_title', 'Bad request.');
            return 'error';
        }
    }

    public function writeCard()
    {
        try {
            $this->writePostService->checkAbuseRequest($this->request->getPostParam('content'));
            DataSource::beginTransaction();
            $cardDTO = new CardDTO();
            $cardDTO->setBbsUID($this->request->getPostParam('bbs_uid'));
            $cardDTO->setTitle($this->request->getPostParam('title'));
            $cardDTO->setPassword($this->request->getPostParam('password'));
            $cardUID = $this->writeCardService->writeCard($cardDTO);
            $createdCardDTO = $this->cardService->getCardDataOnlyByCardUID($cardUID);

            $postDTO = new PostDTO();
            $postDTO->setOrder(0);
            $postDTO->setCardUID($cardUID);
            $postDTO->setCreateDate($createdCardDTO->getOpenDate());
            $postDTO->setBbsUID($this->request->getPostParam('bbs_uid'));
            $postDTO->setName($this->request->getPostParam('name'));
            $postDTO->setContent(new Content($this->request->getPostParam('content')));
            $postDTO->setImage($this->request->getPostParam('image'));
            $postDTO->setIp($this->request->getServerInfo('REMOTE_ADDR'));
            if ($this->request->getFile('image')['size'] > 0) {
                $imageName = $this->fileUploadService->putImage(
                    $this->request->getFile('image'),
                    $cardUID,
                    0
                );
                $postDTO->setImage($imageName);
            }
            $this->writePostService->writePost($postDTO, new Console($this->request->getPostParam('console')));
            DataSource::commit();
            DataSource::clear();
            $this->response->addHeader("Refresh:2; url={$this->request->getServerInfo('HTTP_REFERER')}");
            return 'write';
        } catch (\Exception $e) {
            DataSource::rollBack();
            DataSource::clear();
            $this->response->addHeader('HTTP/1.1 500 Internal Server Error');
            $this->response->addAttribute('error_title', $e->getMessage());
            return 'error';
        }
    }

    public function writePost()
    {
        try {
            $console = new Console($this->request->getPostParam('console'));
            if ($console->hasManageConsole()) {
                $this->mgmtService->apply(
                    $this->request->getPostParam('card_uid'),
                    $this->request->getPostParam('content')
                );
            } else {
                $this->writePostService->checkAbuseRequest($this->request->getPostParam('content'));
                $postDTO = new PostDTO();
                $postDTO->setOrder(
                    $this->postService->getLastPostOrder($this->request->getPostParam('card_uid')) + 1
                );
                $postDTO->setCardUID($this->request->getPostParam('card_uid'));
                $postDTO->setBbsUID($this->request->getPostParam('bbs_uid'));
                $postDTO->setName($this->request->getPostParam('name'));
                $postDTO->setContent(new Content(htmlspecialchars($this->request->getPostParam('content'))));
                $postDTO->setImage($this->request->getPostParam('image'));
                $postDTO->setIp($this->request->getServerInfo('REMOTE_ADDR'));
                if ($this->request->getFile('image')['size'] > 0) {
                    $imageName = $this->fileUploadService->putImage(
                        $this->request->getFile('image'),
                        $this->request->getPostParam('card_uid'),
                        $this->cardService->getCardSize($this->request->getPostParam('card_uid'))
                    );
                    $postDTO->setImage($imageName);
                }
                $this->writePostService->writePost($postDTO, $console);
            }
            $this->response->addHeader("Refresh:2; url={$this->request->getServerInfo('HTTP_REFERER')}");
            return 'write';
        } catch (\Exception $e) {
            $this->response->addHeader('HTTP/1.1 500 Internal Server Error');
            $this->response->addAttribute('error_title', $e->getMessage());
            return 'error';
        }
    }
}