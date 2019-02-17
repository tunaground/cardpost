<?php
namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\UIComponent\CardListNode;
use Tunacan\Bundle\Service\UIComponentServiceInterface;
use Tunacan\MVC\BaseController;
use Tunacan\Bundle\Service\CardServiceInterface;
use Tunacan\Bundle\Service\PostServiceInterface;
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
    /**
     * @Inject
     * @var UIComponentServiceInterface
     */
    private $uiService;

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

    private function getCardList(array $cardDTOList): string
    {
        return array_reduce(
            $this->uiService->drawCardList($cardDTOList),
            function (string $carry, CardListNode $cardListNode) {
                return $carry . $cardListNode;
            }, '');
    }

    private function getCardGroup(array $cardDTOList): string
    {
        $cardOrder = 1;
        return array_reduce(
            $cardDTOList,
            function (string $carry, CardDTO $cardDTO) use (&$cardOrder) {
                $postForm = $this->uiService->drawPostForm($cardDTO);
                $postList = $this->uiService->drawPost(
                    array_merge(
                        $this->postService->getPostWithLimit($cardDTO->getCardUID(), 0, 1),
                        $this->postService->getPostWithLimit(
                            $cardDTO->getCardUID(),
                            ($cardDTO->getSize() < 16) ? 1 : $cardDTO->getSize() - 15,
                            ($cardDTO->getSize() < 16) ? $cardDTO->getSize() : 15
                        )
                    )
                );
                $card = $this->uiService->drawCard($cardDTO, $cardOrder, $postForm, $postList);
                return $carry . $card;
            }, ''
        );
    }

    private function getCardForm(): string
    {
        return $this->uiService->drawCardForm($this->request->getUriArguments('bbsUID'));
    }
}

