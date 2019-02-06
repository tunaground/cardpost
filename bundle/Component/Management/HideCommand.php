<?php
namespace Tunacan\Bundle\Component\Management;

use Tunacan\Bundle\DataObject\PostDao;

class HideCommand implements CommandInterface
{
    /** @var PostDao */
    private $postDAO;
    /** @var int */
    private $postUID;

    public function __construct(PostDao $postDAO, int $postUID)
    {
        $this->postDAO = $postDAO;
        $this->postUID = $postUID;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        try {
            $this->postDAO->updatePostStatus($this->postUID, 2);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}