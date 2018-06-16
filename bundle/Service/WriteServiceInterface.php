<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\Console;
use Tunacan\Bundle\DataObject\PostDto;

interface WriteServiceInterface
{
    public function writePost(PostDto $dto, Console $console);
    public function getLastPostOrder(int $cardUid);
}