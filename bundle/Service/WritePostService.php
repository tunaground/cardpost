<?php

namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\Console;
use Tunacan\Bundle\Component\Content;
use Tunacan\Bundle\DataObject\PostDao;
use Tunacan\Bundle\DataObject\PostDto;
use Tunacan\Bundle\Util\DateTimeBuilder;
use Tunacan\Bundle\Util\Encryptor;

class WritePostService implements WritePostServiceInterface
{
    private $encryptor;
    private $dateTimeBuilder;
    /** @var PostDao */
    private $postDao;

    public function __construct(Encryptor $encryptor, DateTimeBuilder $dateTimeBuilder, PostDao $postDao)
    {
        $this->encryptor = $encryptor;
        $this->dateTimeBuilder = $dateTimeBuilder;
        $this->postDao = $postDao;
    }

    /**
     * @param string $content
     * @throws \Exception
     */
    public function checkAbuseRequest(string $content)
    {
        if (
            (time() - $_SESSION['last_content_time']) < 30
            && $_SESSION['last_content_hash'] == md5($content)
        ) {
            throw new \Exception('Deny.');
        }

        $_SESSION['last_content_time'] = time();
        $_SESSION['last_content_hash'] = md5($content);
    }

    /**
     * @param PostDto $postDto
     * @param Console $console
     * @return null|string
     * @throws \Exception
     */
    public function writePost(PostDto $postDto, Console $console)
    {
        try {
            $this->postDto = $postDto;
            $this->console = $console;
            if (is_null($postDto->getCreateDate())) {
                $postDto->setCreateDate($this->dateTimeBuilder->getCurrentUtcDateTime());
            }
            $postDto->setUserId(
                $this->encryptor->makeTrip(
                    $postDto->getIp()
                    . date('Y-m-d', time())
                    . $postDto->getBbsUid()
                )
            );
            $postDto->setName($this->makeName($postDto->getName()));
            $postDto->setContent($this->makeContent($postDto->getContent(), $console));
            return $this->postDao->InsertPost($postDto);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @return string
     * @throws \Exception
     */
    private function makeName($name)
    {
        $name = ($name == '') ? 'noname' : $name;
        if (preg_match("/([^\#]*)\#(.+)/", $name, $match)) {
            $match[1] = ($match[1] == '') ? 'noname' : $match[1];
            $name = $match[1] . "<b>◆" . $this->encryptor->makeTrip($match[2]) . "</b>";
        }
        if (mb_strlen($name, 'utf-8') > 60) {
            throw new \Exception('Name is too long.');
        }
        return $name;
    }

    /**
     * @param Content $content
     * @param Console $console
     * @return Content
     * @throws \Exception
     */
    private function makeContent(Content $content, Console $console)
    {
        if (!$console->hasOffConsole()) {
            $content->applyAll();
        } else {
            $content->applyBreak();
        }
        if ($console->hasAaConsole()) {
            $content->applyMonaTagAll();
        }
        if ($content->getLength() > 20000) {
            throw new \Exception('Content is too long.');
        }
        return $content;
    }
}