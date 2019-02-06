<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\Management\CommandInterface;
use Tunacan\Bundle\Component\Management\HideCommand;
use Tunacan\Bundle\DataObject\CardDao;
use Tunacan\Bundle\DataObject\PostDao;

class ManagementService implements ManagementServiceInterface
{
    /** @var CardDao */
    private $cardDAO;
    /** @var PostDao */
    private $postDAO;

    public function __construct(CardDao $cardDAO, PostDao $postDAO)
    {
        $this->cardDAO = $cardDAO;
        $this->postDAO = $postDAO;
    }

    /**
     * @param int $cardUID
     * @param string $data
     * @throws \Exception
     */
    public function apply(int $cardUID, string $data)
    {
        try {
            $dataSplit = explode(PHP_EOL, $data);
            if ($this->checkPassword($cardUID, $dataSplit[0])) {
                for ($i = 1; $i < sizeof($dataSplit); $i++) {
                    $cmd = $this->checkCommand($cardUID, $dataSplit[$i]);
                    $cmd->execute();
                }
            } else {
                throw new \Exception('Password not match');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function checkPassword(int $cardUid, string $password): bool
    {
        $card = $this->cardDAO->getCardByCardUid($cardUid);
        return ($card->getPassword() === hash('sha256', trim($password)));
    }

    /**
     * @param int $cardUID
     * @param string $cmdString
     * @return CommandInterface
     * @throws \Exception
     */
    public function checkCommand(int $cardUID, string $cmdString): CommandInterface
    {
        try {
            $cmdSplit = explode('.', $cmdString);
            switch ($cmdSplit[0]) {
                case 'hide':
                    $post = $this->postDAO->getPostByPostOrder($cardUID, $cmdSplit[1]);
                    $cmd = new HideCommand($this->postDAO, $post->getPostUid());
                    break;
                default:
                    throw new \Exception('Command not found');
            }
            return $cmd;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}