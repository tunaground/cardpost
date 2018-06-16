<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\Console;
use Tunacan\Bundle\DataObject\PostDao;
use Tunacan\Bundle\DataObject\PostDto;
use Tunacan\Bundle\Util\DateTimeBuilder;
use Tunacan\Bundle\Util\Encryptor;

class WriteService
{
    private $encryptor;
    private $dateTimeBuilder;
    /** @var PostDao */
    private $postDao;
    /** @var PostDto */
    private $postDto;
    /** @var Console */
    private $console;

    public function __construct(Encryptor $encryptor, DateTimeBuilder $dateTimeBuilder, PostDao $postDao)
    {
        $this->encryptor = $encryptor;
        $this->dateTimeBuilder = $dateTimeBuilder;
        $this->postDao = $postDao;
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
            $this->setOrder();
            $this->setTime();
            $this->setId();
            $this->setName();
            $this->setContent();
            return $this->postDao->InsertPost($this->postDto);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getLastPostOrder(int $cardUid)
    {
        return $this->postDao->getLastPostOrder($cardUid);
    }

    private function setOrder()
    {
        $order = $this->postDao->getLastPostOrder($this->postDto->getCardUid()) + 1;
        $this->postDto->setOrder($order);
    }

    private function setTime()
    {
        $this->postDto->setCreatedate($this->dateTimeBuilder->getCurrentDateTime());
    }

    private function setId()
    {
        $this->postDto->setUserId(
            $this->encryptor->makeTrip(
                $this->postDto->getIp()
                .date('Y-m-d', time())
                .$this->postDto->getBbsUid()
            )
        );
    }

    /**
     * @throws \Exception
     */
    private function setName()
    {
        $name = ($this->postDto->getName() == '') ? 'noname' : $this->postDto->getName();
        if (preg_match("/([^\#]*)\#(.+)/", $name, $match)) {
            $match[1] = ($match[1] == '') ? 'noname' : $match[1];
            $name = $match[1] . "<b>◆" . $this->encryptor->makeTrip($match[2]) . "</b>";
        }
        if (mb_strlen($name, 'utf-8') > 60) {
            throw new \Exception('Name is too long.');
        }
        $this->postDto->setName($name);
    }

    /**
     * @throws \Exception
     */
    private function setContent()
    {
        $content = $this->postDto->getContent();
        if (!$this->console->hasOffConsole()) {
            $content->applyAll();
        } else {
            $content->applyBreak();
        }
        if ($this->console->hasAaConsole()) {
            $content = '<p class="mona">' . $content . '</p>';
        }
        if ($content->getLength() > 20000) {
            throw new \Exception('Content is too long.');
        }
        $this->postDto->setContent($content);
    }
}