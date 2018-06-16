<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\PostDto;

interface PostServiceInterface
{
    public function getLastPostOrder(int $cardUid): int;
    public function getPostByCardUid(int $cardUid): array;
    public function getPostWithLimit(int $cardUid, int $start, int $count): array;
    public function getPostByPostOrder(int $cardUid, int $postOrder): PostDto;
    public function insertPost(PostDto $postDto);
}
