<?php

namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\UIComponent\CardListNode;
use Tunacan\MVC\BaseController;
use Tunacan\Bundle\Service\CardServiceInterface;
use Tunacan\Bundle\DataObject\CardDto;

class ListController extends BaseController
{
    /**
     * @Inject
     * @var CardServiceInterface
     */
    private $cardService;

    public function index()
    {
        $bbsUid = $this->request->getUriArguments('bbsUid');
        $page = $this->request->getUriArguments('page');
        $cardDtoList = $this->cardService->getCardListByBbsUid($bbsUid, $page);
        $previousBtn = ($page <= 1)? 'hide' : '';
        $nextBtn = (sizeof($cardDtoList) < 10)? 'hide' : '';

        $this->response->addAttribute('bbs_uid', $bbsUid);
        $this->response->addAttribute('card_list', $this->getCardList($cardDtoList));
        $this->response->addAttribute('previous_btn', $previousBtn);
        $this->response->addAttribute('next_btn', $nextBtn);
        $this->response->addAttribute('previous_page', $page - 1);
        $this->response->addAttribute('next_page', $page + 1);
        return 'list';
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
}

