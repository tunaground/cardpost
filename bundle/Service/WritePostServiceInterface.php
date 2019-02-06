<?php

namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\Console;
use Tunacan\Bundle\DataObject\PostDTO;

interface WritePostServiceInterface
{
    public function checkAbuseRequest(string $content);

    public function writePost(PostDTO $DTO, Console $console);
}