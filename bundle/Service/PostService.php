<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\PostDao;
use Tunacan\Bundle\DataObject\PostDto;

class PostService implements PostServiceInterface
{
    /** @var PostDao */
    private $postDao;

    public function __construct(PostDao $postDao)
    {
        $this->postDao = $postDao;
    }

    public function getLastPostOrder(int $cardUid): int
    {
        return $this->postDao->getLastPostOrder($cardUid);
    }

    public function getPostByCardUid(int $cardUid): array
    {
        return $this->postDao->getPostByCardUid($cardUid);
    }

    public function getPostWithLimit(int $cardUid, int $start, int $count): array
    {
        return $this->postDao->getPostWithLimit($cardUid, $start, $count);
    }

    /**
     * @param int $cardUid
     * @param int $postOrder
     * @return PostDto
     * @throws \Exception
     */
    public function getPostByPostOrder(int $cardUid, int $postOrder): PostDto
    {
        try {
            return $this->postDao->getPostByPostOrder($cardUid, $postOrder);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param PostDto $postDto
     * @return null|string
     * @throws \Exception
     */
    public function insertPost(PostDto $postDto)
    {
        try {
            return $this->postDao->InsertPost($postDto);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
