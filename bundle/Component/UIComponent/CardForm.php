<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\MVC\AbstractComponent;

class CardForm extends AbstractComponent
{
    protected $htmlTemplateName = 'cardForm';
    private $bbsUID;

    public function getObject()
    {
        $cardForm = new CardForm($this->loader, $this->parser);
        return $cardForm;
    }

    public function setBbsUID(string $bbsUID): void
    {
        $this->bbsUID = $bbsUID;
    }

    public function __toString()
    {
        return $this->parser->parse($this->loader->load($this->htmlTemplateName), [
            'bbsUID' => $this->bbsUID
        ]);
    }
}