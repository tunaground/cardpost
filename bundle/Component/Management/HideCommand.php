<?php
namespace Tunacan\Bundle\Component\Management;

use Tunacan\Bundle\DataObject\PostDAO;

class HideCommand implements CommandInterface
{
    /** @var PostDAO */
    private $postDAO;
    /** @var int */
    private $postUID;

    public function __construct(PostDAO $postDAO, int $postUID)
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