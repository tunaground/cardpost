<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\UIComponent\Card;
use Tunacan\Bundle\Component\UIComponent\CardForm;
use Tunacan\Bundle\Component\UIComponent\CardListNode;
use Tunacan\Bundle\Component\UIComponent\Post;
use Tunacan\Bundle\Component\UIComponent\PostForm;
use Tunacan\Bundle\DataObject\CardDTO;
use Tunacan\Bundle\DataObject\PostDTO;

interface UIComponentServiceInterface
{
    /**
     * @param CardDTO[] $cardDTOList
     * @return CardListNode[]
     */
    public function drawCardList(array $cardDTOList): array;

    /**
     * @param PostDTO[] $postDTOList
     * @return Post[]
     */
    public function drawPost(array $postDTOList): array;

    public function drawCard(CardDTO $cardDTO, int $order, PostForm $postForm, array $postList): Card;

    public function drawPostForm(CardDTO $cardDTO): PostForm;

    public function drawCardForm(string $bbsUID): CardForm;
}