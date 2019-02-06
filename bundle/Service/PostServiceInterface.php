<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\PostDTO;

interface PostServiceInterface
{
    public function getLastPostOrder(int $cardUID): int;

    public function getPostByCardUID(int $cardUID): array;

    public function getPostWithLimit(int $cardUID, int $start, int $count): array;

    public function getPostByPostOrder(int $cardUID, int $postOrder): PostDTO;

    public function insertPost(PostDTO $postDTO);
}
