<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\Console;
use Tunacan\Bundle\DataObject\PostDto;

interface WritePostServiceInterface
{
    public function checkAbuseRequest(string $content);
    public function writePost(PostDto $dto, Console $console);
}