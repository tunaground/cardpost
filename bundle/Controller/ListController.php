<?php
namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\UIComponent\CardListNode;
use Tunacan\MVC\BaseController;
use Tunacan\Bundle\Service\CardServiceInterface;
use Tunacan\Bundle\DataObject\CardDTO;

class ListController extends BaseController
{
    /**
     * @Inject
     * @var CardServiceInterface
     */
    private $cardService;

    public function index()
    {
        try {
            $bbsUID = $this->request->getUriArguments('bbsUID');
            $page = $this->request->getUriArguments('page');
            $cardDTOList = $this->cardService->getCardListByBbsUID($bbsUID, $page);
            $previousBtn = ($page <= 1) ? 'hide' : '';
            $nextBtn = (sizeof($cardDTOList) < 10) ? 'hide' : '';

            $this->response->addAttribute('bbs_uid', $bbsUID);
            $this->response->addAttribute('card_list', $this->getCardList($cardDTOList));
            $this->response->addAttribute('previous_btn', $previousBtn);
            $this->response->addAttribute('next_btn', $nextBtn);
            $this->response->addAttribute('previous_page', $page - 1);
            $this->response->addAttribute('next_page', $page + 1);
            return 'list';
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
}

