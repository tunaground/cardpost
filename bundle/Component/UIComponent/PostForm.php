<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\MVC\AbstractComponent;

class PostForm extends AbstractComponent
{
    protected static $templateName = 'postForm';
    private $bbsUID;
    private $cardUID;

    public function setBbsUID(string $bbsUID): void
    {
        $this->bbsUID = $bbsUID;
    }

    public function setCardUID(string $cardUID): void
    {
        $this->cardUID = $cardUID;
    }

    public function __toString()
    {
        return $this->parser->parse($this->template, [
            'bbsUID' => $this->bbsUID,
            'cardUID' => $this->cardUID
        ]);
    }
}