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

class IndexController extends BaseController
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
        $body = '<head><script src="/js/index.js"></script><link rel="stylesheet" type="text/css" href="http://public.tunaground.net/data/default.css"/></head>';
        $cardOrder = 1;
        $body .= array_reduce(
            $this->cardService->getCardListByBbsUid($this->request->getUriArguments('bbsUid')),
            function (string $carry, CardDto $cardDto) use (&$cardOrder) {
                $postForm = $this->app->get(PostForm::class)->getObject();
                $postForm->setBbsUid($cardDto->getBbsUid());
                $postForm->setCardUid($cardDto->getCardUid());
                $card = $this->app->get(Card::class)->getObject();
                $card->setOrder($cardOrder++);
                $card->setCardDto($cardDto);
                $card->setPostList(array_reduce(
                    array_merge(
                        $this->postService->getPostWithLimit($cardDto->getCardUid(),0,1),
                        $this->postService->getPostWithLimit(
                            $cardDto->getCardUid(),
                            ($cardDto->getSize() < 16)? 1 : $cardDto->getSize() - 15,
                            ($cardDto->getSize() < 16)? $cardDto->getSize() : 15
                        )
                    ),
                    function (array $postList, PostDto $postDto) {
                        $post = $this->app->get(Post::class)->getObject();
                        $post->setPostDto($postDto);
                        $postList[] = $post;
                        return $postList;
                    }, []));
                $card->setPostForm($postForm);
                return $carry . $card->__toString();
            }, '');
        $cardForm = $this->app->get(CardForm::class)->getObject();
        $cardForm->setBbsUid($this->request->getUriArguments('bbsUid'));
        $body .= $cardForm;
        $this->response->setBody($body);
        return $this->response;
    }
}

