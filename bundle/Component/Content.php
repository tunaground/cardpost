<?php
namespace Tunacan\Bundle\Component;

class Content
{
    private $contentString;

    public function __construct($contentString = '')
    {
        $this->setContentString($contentString);
    }

    public function setContentString(string $contentString)
    {
        $this->contentString = $contentString;
    }

    public function applyAll()
    {
        $this->applyBreak();
        $this->applyMonaTag();
        $this->applyHorizonTag();
        $this->applyColorTag();
        $this->applyColorAndShadowTag();
        $this->applyRubyTag();
        $this->applyDice();
    }

    public function applyBreak()
    {
        $this->contentString = str_replace(PHP_EOL, '<br />', $this->contentString);
    }

    public function applyMonaTagAll()
    {
        $this->contentString = "<p class='mona'>{$this->contentString}</p>";
    }

    public function applyMonaTag()
    {
        $this->contentString = preg_replace("/(\.aa)/", '<p class="mona">', $this->contentString);
        $this->contentString = preg_replace("/(aa\.)/", '</p>', $this->contentString);
    }

    public function applyHorizonTag()
    {
        $this->contentString = preg_replace("/(\.hr\.)/", '<hr />', $this->contentString);
    }

    public function applyColorTag()
    {
        $this->contentString = preg_replace("/&lt;clr (#?[a-z0-9]+)&gt;(((?!&lt;\/clr&gt;)[\s\S])+)&lt;\/clr&gt;/",
            '<span style="color: \\1">\\2</span>', $this->contentString, -1);
    }

    public function applyColorAndShadowTag()
    {
        $this->contentString = preg_replace("/&lt;clr (#?[a-z0-9]+) (#?[a-z0-9]+)&gt;(((?!&lt;\/clr&gt;)[\s\S])+)&lt;\/clr&gt;/",
            '<span style="color: \\1; text-shadow: 0px 0px 6px \\2;">\\3</span>', $this->contentString, -1);
    }

    public function applyRubyTag()
    {
        $this->contentString = preg_replace("/&lt;ruby ([a-zA-Z0-9가-힣一-龥\s]+)&gt;(((?!&lt;\/ruby&gt;)[\s\S])+)&lt;\/ruby&gt;/",
            '<ruby>\\2<rt>\\1</rt></ruby>', $this->contentString, -1);
    }

    public function applyDice()
    {
        $tempText = preg_split("/(\.dice )(0|-?[1-9][0-9]*)( )(0|-?[1-9][0-9]*)(\.)/", $this->contentString, -1);
        if (preg_match_all("/(\.dice )(0|-?[1-9][0-9]*)( )(0|-?[1-9][0-9]*)(\.)/", $this->contentString, $matches,
            PREG_SET_ORDER)) {
            $diceResult = [];
            for ($i = 0; $i < sizeof($matches); $i++) {
                $diceResult[$i] = mt_rand($matches[$i][2], $matches[$i][4]);
            }
            $this->contentString = $tempText[0] . '<span class="dice">' . $matches[0][0] . ' = ' . $diceResult[0] . '</span>';
            for ($i = 1; $i < sizeof($diceResult); $i++) {
                $this->contentString .= $tempText[$i] . '<span class="dice">' . $matches[$i][0] . ' = ' . $diceResult[$i] . '</span>';
            }
            $this->contentString .= $tempText[$i];
        }
    }

    public function makeAsciiArtContent()
    {
        $this->contentString = sprintf('<p class="mona">%s</p>', $this->contentString);
    }

    public function applyAnchor($bbsUID, $cardUID): self
    {
        $this->contentString = preg_replace_callback(
            "/([a-z]*)&gt;([0-9]*)&gt;([0-9]*)-?([0-9]*)/",
            function ($matches) use ($bbsUID, $cardUID) {
                $bbsUID = ($matches[1]) ?: $bbsUID;
                $cardUID = ($matches[2]) ?: $cardUID;
                $startPostUID = $matches[3];
                $endPostUID = ($matches[4]) ?: $startPostUID;
                return "<a href='/trace/{$bbsUID}/{$cardUID}/{$startPostUID}/{$endPostUID}'>{$matches[0]}</a>";
            }, $this->contentString);
        return $this;
    }

    public function getLength()
    {
        return mb_strlen($this->contentString, 'utf-8');
    }

    public function __toString()
    {
        return $this->contentString;
    }
}
