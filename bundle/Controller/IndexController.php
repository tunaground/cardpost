<?php
namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\UIComponent\CardForm;
use Tunacan\Bundle\Component\UIComponent\CardListNode;
use Tunacan\Bundle\Component\UIComponent\Post;
use Tunacan\Bundle\Component\UIComponent\PostForm;
use Tunacan\Bundle\DataObject\PostDTO;
use Tunacan\MVC\BaseController;
use Tunacan\Bundle\Service\CardServiceInterface;
use Tunacan\Bundle\Service\PostServiceInterface;
use Tunacan\Bundle\Component\UIComponent\Card;
use Tunacan\Bundle\DataObject\CardDTO;

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
        try {
            $cardDTOList = $this->cardService->getCardListByBbsUID($this->request->getUriArguments('bbsUID'));
            $this->response->addAttribute('bbs_uid', $this->request->getUriArguments('bbsUID'));
            $this->response->addAttribute('card_list', $this->getCardList($cardDTOList));
            $this->response->addAttribute('card_group', $this->getCardGroup($cardDTOList));
            $this->response->addAttribute('card_form', $this->getCardForm());
            return 'index';
        } catch (\Exception $e) {
            $this->response->addHeader('HTTP/1.1 500 Internal Server Error');
            $this->response->addAttribute('error_title', $e->getMessage());
            return 'error';
        }
    }

    /**
     * @param CardDTO[] $cardDTOList
     * @return string
     */
    private function getCardList(array $cardDTOList)
    {
        $cardOrder = 1;
        return array_reduce(
            $cardDTOList,
            function (string $carry, CardDTO $cardDTO) use (&$cardOrder) {
                $cardListNode = $this->app->get(CardListNode::class)->getObject();
                $cardListNode->setOrder($cardOrder++);
                $cardListNode->setBbsUID($cardDTO->getBbsUID());
                $cardListNode->setCardUID($cardDTO->getCardUID());
                $cardListNode->setTitle($cardDTO->getTitle());
                $cardListNode->setOwner($cardDTO->getOwner());
                $cardListNode->setRefreshDate($cardDTO->getRefreshDate());
                $cardListNode->setSize($cardDTO->getSize());
                return $carry . $cardListNode->__toString();
            },
            ''
        );
    }

    /**
     * @param CardDTO[] $cardDTOList
     * @return string
     */
    private function getCardGroup(array $cardDTOList)
    {
        $cardOrder = 1;
        return array_reduce(
            $cardDTOList,
            function (string $carry, CardDTO $cardDTO) use (&$cardOrder) {
                $postForm = $this->app->get(PostForm::class)->getObject();
                $postForm->setBbsUID($cardDTO->getBbsUID());
                $postForm->setCardUID($cardDTO->getCardUID());
                $card = $this->app->get(Card::class)->getObject();
                $card->setOrder($cardOrder++);
                $card->setCardDTO($cardDTO);
                $card->setPostList(array_reduce(
                    array_merge(
                        $this->postService->getPostWithLimit($cardDTO->getCardUID(), 0, 1),
                        $this->postService->getPostWithLimit(
                            $cardDTO->getCardUID(),
                            ($cardDTO->getSize() < 16) ? 1 : $cardDTO->getSize() - 15,
                            ($cardDTO->getSize() < 16) ? $cardDTO->getSize() : 15
                        )
                    ),
                    function (array $postList, PostDTO $postDTO) {
                        $post = $this->app->get(Post::class)->getObject();
                        $post->setPostDTO($postDTO);
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
        $cardForm->setBbsUID($this->request->getUriArguments('bbsUID'));
        return $cardForm;
    }
}

