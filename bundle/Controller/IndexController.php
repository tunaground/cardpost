<?php

namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\Post;
use Tunacan\Bundle\Component\PostForm;
use Tunacan\Bundle\DataObject\PostDto;
use Tunacan\MVC\BaseController;
use Tunacan\Bundle\Service\CardServiceInterface;
use Tunacan\Bundle\Service\PostServiceInterface;
use Tunacan\Bundle\Component\Card;
use Tunacan\Bundle\DataObject\CardDto;
use Tunacan\Util\ContextParser;

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
        $cardOrder = 1;
        $this->response->setBody(array_reduce(
            $this->cardService->getCardListByBbsUid($this->request->getUriArguments('bbsUid')),
            function (string $carry, CardDto $cardDto) use ($cardOrder) {
                $postForm = $this->app->get(PostForm::class);
                $postForm->setBbsUid($cardDto->getBbsUid());
                $postForm->setCardUid($cardDto->getCardUid());
                $card = $this->app->get(Card::class);
                $card->setOrder($cardOrder++);
                $card->setCardDto($cardDto);
                $card->setPostList(array_reduce(
                    $this->postService->getPostByCardUid($cardDto->getCardUid()),
                    function (array $postList, PostDto $postDto) {
                        $post = new Post($this->app->get('view.template.loader'), $this->app->get(ContextParser::class));
                        $post->setPostDto($postDto);
                        $postList[] = $post;
                        return $postList;
                    }, []));
                $card->setPostForm($postForm);
                return $carry . $card->__toString();
            }, ''));
        return $this->response;
    }
}

