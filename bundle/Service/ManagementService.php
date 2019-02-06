<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\Component\Management\CommandInterface;
use Tunacan\Bundle\Component\Management\DenyCommand;
use Tunacan\Bundle\Component\Management\HideCommand;
use Tunacan\Bundle\DataObject\CardDAO;
use Tunacan\Bundle\DataObject\DenyDAO;
use Tunacan\Bundle\DataObject\PostDAO;
use Tunacan\Bundle\Util\DateTimeBuilder;

class ManagementService implements ManagementServiceInterface
{
    /** @var CardDAO */
    private $cardDAO;
    /** @var PostDAO */
    private $postDAO;
    /** @var DenyDAO */
    private $denyDAO;
    /** @var DateTimeBuilder */
    private $dateTimeBuilder;

    public function __construct(
        CardDAO $cardDAO,
        PostDAO $postDAO,
        DenyDAO $denyDAO,
        DateTimeBuilder $dateTimeBuilder
    ) {
        $this->cardDAO = $cardDAO;
        $this->postDAO = $postDAO;
        $this->denyDAO = $denyDAO;
        $this->dateTimeBuilder = $dateTimeBuilder;
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
                throw new \Exception('Password not match.');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $cardUID
     * @param string $password
     * @return bool
     * @throws \Exception
     */
    public function checkPassword(int $cardUID, string $password): bool
    {
        try {
            $card = $this->cardDAO->getCardByCardUID($cardUID);
            return ($card->getPassword() === hash('sha256', trim($password)));
        } catch (\Exception $e) {
            throw $e;
        }
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
                    $cmd = new HideCommand($this->postDAO, $post->getPostUID());
                    break;
                case 'deny':
                    $cmd = new DenyCommand(
                        $this->denyDAO,
                        $cardUID,
                        trim($cmdSplit[1]),
                        $this->dateTimeBuilder->getCurrentUtcDateTime()
                    );
                    break;
                default:
                    throw new \Exception('Command not found.');
            }
            return $cmd;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}