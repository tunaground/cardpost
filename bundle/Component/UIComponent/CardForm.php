<?php
namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\MVC\AbstractComponent;

class CardForm extends AbstractComponent
{
    protected $htmlTemplateName = 'cardForm';
    private $bbsUid;

    public function getObject()
    {
        $cardForm = new CardForm($this->loader, $this->parser);
        return $cardForm;
    }

    public function setBbsUid(string $bbsUid): void
    {
        $this->bbsUid = $bbsUid;
    }

    public function __toString()
    {
        return $this->parser->parse($this->loader->load($this->htmlTemplateName), [
            'bbsUid' => $this->bbsUid
        ]);
    }
}