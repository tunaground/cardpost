<?php
namespace Tunacan\Bundle\Controller;

use Tunacan\Bundle\Component\UIComponent\Post;
use Tunacan\Bundle\Component\UIComponent\PostForm;
use Tunacan\Bundle\DataObject\PostDTO;
use Tunacan\MVC\BaseController;
use Tunacan\Bundle\Service\CardServiceInterface;
use Tunacan\Bundle\Service\PostServiceInterface;
use Tunacan\Bundle\Component\UIComponent\Card;
use Tunacan\Bundle\DataObject\CardDTO;

class TraceController extends BaseController
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
            $this->response->addAttribute('card_section', $this->getCardSection());
            return 'trace';
        } catch (\Exception $e) {
            $this->response->addHeader('HTTP/1.1 500 Internal Server Error');
            $this->response->addAttribute('error_title', $e->getMessage());
            return 'error';
        }
    }

    /**
     * @return Card
     * @throws \Exception
     */
    private function getCardSection()
    {
        try {
            $cardDTO = $this->cardService->getCardByCardUID($this->request->getUriArguments('cardUID'));
            $postForm = $this->app->get(PostForm::class)->getObject();
            $postForm->setBbsUID($cardDTO->getBbsUID());
            $postForm->setCardUID($cardDTO->getCardUID());
            $card = $this->app->get(Card::class)->getObject();
            $card->setOrder(0);
            $card->setCardDTO($cardDTO);
            $card->setPostList(array_reduce(
                array_merge(
                    $this->postService->getPostWithLimit($cardDTO->getCardUID(), 0, 1),
                    $this->getPostList($cardDTO)
                ),
                function (array $postList, PostDTO $postDTO) {
                    $post = $this->app->get(Post::class)->getObject();
                    $post->setPostDTO($postDTO);
                    $postList[] = $post;
                    return $postList;
                }, []));
            $card->setPostForm($postForm);
            return $card;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param CardDTO $cardDTO
     * @return array
     * @throws \Exception
     */
    private function getPostList(CardDTO $cardDTO): array
    {
        try {
            $startPostUID = $this->request->getUriArguments('startPostUID');
            $endPostUID = $this->request->getUriArguments('endPostUID');
            $postLimitStart = 1;
            $postLimitEnd = $cardDTO->getSize() - 1;
            if ($startPostUID === 'recent') {
                $postLimitStart = ($cardDTO->getSize() < 16) ? 1 : $cardDTO->getSize() - 15;
                $postLimitEnd = ($cardDTO->getSize() < 16) ? $cardDTO->getSize() : 15;
            }
            if (is_numeric($startPostUID)) {
                $postLimitStart = $startPostUID;
                $postLimitEnd = 1;
            }
            if (is_numeric($endPostUID)) {
                $postLimitEnd = $endPostUID - $startPostUID + 1;
            }
            return $this->postService->getPostWithLimit(
                $cardDTO->getCardUID(),
                $postLimitStart,
                $postLimitEnd
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

