<?php

namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\UIComponent\CardForm;
use Tunacan\Bundle\Component\UIComponent\CardListNode;
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
        $cardDtoList = $this->cardService->getCardListByBbsUid($this->request->getUriArguments('bbsUid'));
        $this->response->addAttribute('bbs_uid', $this->request->getUriArguments('bbsUid'));
        $this->response->addAttribute('card_list', $this->getCardList($cardDtoList));
        $this->response->addAttribute('card_group', $this->getCardGroup($cardDtoList));
        $this->response->addAttribute('card_form', $this->getCardForm());
        return 'index';
    }

    /**
     * @param CardDto[] $cardDtoList
     * @return string
     */
    private function getCardList(array $cardDtoList)
    {
        $cardOrder = 1;
        return array_reduce(
            $cardDtoList,
            function (string $carry, CardDto $cardDto) use (&$cardOrder) {
                $cardListNode = $this->app->get(CardListNode::class)->getObject();
                $cardListNode->setOrder($cardOrder++);
                $cardListNode->setBbsUid($cardDto->getBbsUid());
                $cardListNode->setCardUid($cardDto->getCardUid());
                $cardListNode->setTitle($cardDto->getTitle());
                $cardListNode->setOwner($cardDto->getOwner());
                $cardListNode->setRefreshDate($cardDto->getRefreshDate());
                $cardListNode->setSize($cardDto->getSize());
                return $carry . $cardListNode->__toString();
            },
            ''
        );
    }

    /**
     * @param CardDto[] $cardDtoList
     * @return string
     */
    private function getCardGroup(array $cardDtoList)
    {
        $cardOrder = 1;
        return array_reduce(
            $cardDtoList,
            function (string $carry, CardDto $cardDto) use (&$cardOrder) {
                $postForm = $this->app->get(PostForm::class)->getObject();
                $postForm->setBbsUid($cardDto->getBbsUid());
                $postForm->setCardUid($cardDto->getCardUid());
                $card = $this->app->get(Card::class)->getObject();
                $card->setOrder($cardOrder++);
                $card->setCardDto($cardDto);
                $card->setPostList(array_reduce(
                    array_merge(
                        $this->postService->getPostWithLimit($cardDto->getCardUid(), 0, 1),
                        $this->postService->getPostWithLimit(
                            $cardDto->getCardUid(),
                            ($cardDto->getSize() < 16) ? 1 : $cardDto->getSize() - 15,
                            ($cardDto->getSize() < 16) ? $cardDto->getSize() : 15
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
    }

    private function getCardForm()
    {
        $cardForm = $this->app->get(CardForm::class)->getObject();
        $cardForm->setBbsUid($this->request->getUriArguments('bbsUid'));
        return $cardForm;
    }
}

