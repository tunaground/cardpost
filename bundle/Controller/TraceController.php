<?php

namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\UIComponent\CardForm;
use Tunacan\Bundle\Component\UIComponent\Post;
use Tunacan\Bundle\Component\UIComponent\PostForm;
use Tunacan\Bundle\DataObject\PostDto;
use Tunacan\MVC\BaseController;
use Tunacan\Bundle\Service\CardServiceInterface;
use Tunacan\Bundle\Service\PostServiceInterface;
use Tunacan\Bundle\Component\UIComponent\Card;
use Tunacan\Bundle\DataObject\CardDto;

class TraceController extends BaseController
{
    /**
     * @Inject
     * @var CardServiceInterface
     */
    private $cardService;
    /**
     * @Inject
     * @var PostServiceInterface
     */
    private $postService;

    public function index()
    {
        $body = '<head><link rel="stylesheet" type="text/css" href="http://public.tunaground.net/data/default.css"/></head>';
        $cardDto = $this->cardService->getCardByCardUid($this->request->getUriArguments('cardUid'));
        $postForm = $this->app->get(PostForm::class)->getObject();
        $postForm->setBbsUid($cardDto->getBbsUid());
        $postForm->setCardUid($cardDto->getCardUid());
        $card = $this->app->get(Card::class)->getObject();
        $card->setOrder(0);
        $card->setCardDto($cardDto);
        $card->setPostList(array_reduce(
            array_merge(
                $this->postService->getPostWithLimit($cardDto->getCardUid(),0,1),
                $this->getPostList($cardDto)
            ),
            function (array $postList, PostDto $postDto) {
                $post = $this->app->get(Post::class)->getObject();
                $post->setPostDto($postDto);
                $postList[] = $post;
                return $postList;
            }, []));
        $card->setPostForm($postForm);
        $body .= $card->__toString();
        $this->response->setBody($body);
        return $this->response;
    }

    private function getPostList(CardDto $cardDto): array
    {
        $startPostUid = $this->request->getUriArguments('startPostUid');
        $endPostUid = $this->request->getUriArguments('endPostUid');
        $postLimitStart = 1;
        $postLimitEnd = $cardDto->getSize() - 1;
        if ($startPostUid === 'recent') {
            $postLimitStart = ($cardDto->getSize() < 16)? 1 : $cardDto->getSize() - 15;
            $postLimitEnd = ($cardDto->getSize() < 16)? $cardDto->getSize() : 15;
        }
        if (is_numeric($startPostUid)) {
            $postLimitStart = $startPostUid;
            $postLimitEnd = 1;
        }
        if (is_numeric($endPostUid)) {
            $postLimitEnd = $endPostUid - $startPostUid + 1;
        }
        return $this->postService->getPostWithLimit(
            $cardDto->getCardUid(),
            $postLimitStart,
            $postLimitEnd
        );
    }
}

