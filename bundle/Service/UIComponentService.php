<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\UIComponent\Card;
use Tunacan\Bundle\Component\UIComponent\CardForm;
use Tunacan\Bundle\Component\UIComponent\CardListNode;
use Tunacan\Bundle\Component\UIComponent\Post;
use Tunacan\Bundle\Component\UIComponent\PostForm;
use Tunacan\Bundle\DataObject\CardDTO;
use Tunacan\Bundle\DataObject\ConfigDAO;
use Tunacan\Bundle\DataObject\PostDTO;
use Tunacan\Bundle\Util\DateTimeBuilder;
use Tunacan\Util\ContextParser;
use Tunacan\Util\LoaderInterface;

class UIComponentService implements UIComponentServiceInterface
{
    /** @var LoaderInterface */
    private $loader;
    /** @var ContextParser */
    private $parser;
    /** @var ConfigDAO */
    private $configDAO;
    /** @var DateTimeBuilder */
    private $dateTimeBuilder;

    /**
     * UIComponentService constructor.
     * @Inject({"loader" = "view.template.loader"})
     * @param LoaderInterface $loader
     * @param ContextParser $contextParser
     * @param ConfigDAO $configDAO
     * @param DateTimeBuilder $dateTimeBuilder
     */
    public function __construct(
        LoaderInterface $loader,
        ContextParser $contextParser,
        ConfigDAO $configDAO,
        DateTimeBuilder $dateTimeBuilder
    ) {
        $this->loader = $loader;
        $this->parser = $contextParser;
        $this->configDAO = $configDAO;
        $this->dateTimeBuilder = $dateTimeBuilder;
    }

    /**
     * @param CardDTO[] $cardDTOList
     * @return CardListNode[]
     */
    public function drawCardList(array $cardDTOList): array
    {
        $cardOrder = 1;
        return array_reduce(
            $cardDTOList,
            function (array $carry, CardDTO $cardDTO) use (&$cardOrder) {
                $cardListNode = new CardListNode($this->loader, $this->parser);
                $cardListNode->setOrder($cardOrder++);
                $cardListNode->setBbsUID($cardDTO->getBbsUID());
                $cardListNode->setCardUID($cardDTO->getCardUID());
                $cardListNode->setTitle($cardDTO->getTitle());
                $cardListNode->setOwner($cardDTO->getOwner());
                $cardListNode->setRefreshDate($cardDTO->getRefreshDate());
                $cardListNode->setSize($cardDTO->getSize());
                $cardListNode->setDateFormat($this->configDAO->getConfigByKey('format.date'));
                $cardListNode->setTimezone($this->dateTimeBuilder->getUserTimezone());
                $carry[] = $cardListNode;
                return $carry;
            }, []
        );
    }

    /**
     * @param PostDTO[] $postDTOList
     * @return Post[]
     */
    public function drawPost(array $postDTOList): array
    {
        return array_reduce($postDTOList,
            function (array $carry, PostDTO $postDTO) {
                $post = new Post($this->loader, $this->parser);
                $post->setTimezone($this->dateTimeBuilder->getUserTimezone());
                $post->setDateFormat($this->configDAO->getConfigByKey('format.date'));
                $post->setImageDomain($this->configDAO->getConfigByKey('image.domain'));
                $post->setPostDTO($postDTO);
                $carry[] = $post;
                return $carry;
            }, []
        );
    }

    /**
     * @param CardDTO $cardDTO
     * @param int $order
     * @param PostForm $postForm
     * @param array $postList
     * @return Card
     * @throws \Exception
     */
    public function drawCard(CardDTO $cardDTO, int $order, PostForm $postForm, array $postList): Card
    {
        try {
            $card = new Card($this->loader, $this->parser);
            $card->setDateFormat($this->configDAO->getConfigByKey('format.date'));
            $card->setTimezone($this->dateTimeBuilder->getUserTimezone());
            $card->setOrder($order);
            $card->setPostForm($postForm);
            $card->setPostList($postList);
            $card->setCardDTO($cardDTO);
            return $card;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function drawPostForm(CardDTO $cardDTO): PostForm
    {
        $postForm = new PostForm($this->loader, $this->parser);
        $postForm->setBbsUID($cardDTO->getBbsUID());
        $postForm->setCardUID($cardDTO->getCardUID());
        return $postForm;
    }

    public function drawCardForm(string $bbsUID): CardForm
    {
        $cardForm = new CardForm($this->loader, $this->parser);
        $cardForm->setBbsUID($bbsUID);
        return $cardForm;
    }
}