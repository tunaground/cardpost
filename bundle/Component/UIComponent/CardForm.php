<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\MVC\AbstractComponent;

class CardForm extends AbstractComponent
{
    protected static $templateName = 'cardForm';
    private $bbsUID;

    public function setBbsUID(string $bbsUID): void
    {
        $this->bbsUID = $bbsUID;
    }

    public function __toString()
    {
        return $this->parser->parse($this->template, [
            'bbsUID' => $this->bbsUID
        ]);
    }
}