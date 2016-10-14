<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

class EditorToolbar
{
    protected $buttons;

    public function __construct(array $buttons = [])
    {
        $this->buttons = $buttons;
    }

    public function config()
    {
        return implode(',', $this->buttons);
    }

    public function add($button)
    {
        $buttons = $this->buttons;
        $buttons[] = $button;
        return new static($buttons);
    }

    // Buttons

    public function bold()
    {
        return $this->add('bold');
    }

    public function italic()
    {
        return $this->add('italic');
    }

    public function strikethrough()
    {
        return $this->add('strikethrough');
    }

    public function clearFormatting()
    {
        return $this->add('removeformat');
    }

    public function bulletList()
    {
        return $this->add('bullist');
    }

    public function numberedList()
    {
        return $this->add('numlist');
    }

    public function link()
    {
        return $this->add('link');
    }

    public function unlink()
    {
        return $this->add('unlink');
    }

    public function horizontalRule()
    {
        return $this->add('hr');
    }

    public function undo()
    {
        return $this->add('undo');
    }

    public function redo()
    {
        return $this->add('redo');
    }

    public function history()
    {
        return $this
            ->undo()
            ->redo();
    }

    public function styleDropdown()
    {
        return $this->add('styleselect');
    }

    public function specialCharacters()
    {
        return $this->add('charmap');
    }

    public function quotation()
    {
        return $this->add('blockquote');
    }

    public function help()
    {
        return $this->add('wp_help');
    }

    public function spellchecker()
    {
        return $this->add('spellchecker');
    }

    public function fullscreen()
    {
        return $this->add('fullscreen');
    }

    public function distractionFreeWriting()
    {
        return $this->add('dfw');
    }
}
