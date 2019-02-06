<?php

namespace Tunacan\Bundle\Component\UIComponent;

use Tunacan\MVC\AbstractComponent;

class PostForm extends AbstractComponent
{
    protected $htmlTemplateName = 'postForm';
    private $bbsUID;
    private $cardUID;

    public function getObject()
    {
        $postForm = new PostForm($this->loader, $this->parser);
        return $postForm;
    }

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
        return $this->parser->parse($this->loader->load($this->htmlTemplateName), [
            'bbsUID' => $this->bbsUID,
            'cardUID' => $this->cardUID
        ]);
    }
}