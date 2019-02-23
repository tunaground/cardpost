<?php
namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\UIComponent\CardListNode;
use Tunacan\Bundle\Service\UIComponentServiceInterface;
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
    /**
     * @Inject
     * @var UIComponentServiceInterface
     */
    private $uiService;

    public function run(): string
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
        return array_reduce(
            $this->uiService->drawCardList($cardDTOList),
            function (string $carry, CardListNode $cardListNode) {
                return $carry . $cardListNode;
            }, '');
    }
}

