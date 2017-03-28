<?php

namespace Creuna\ObjectiveWpAdmin\Pages;

use Creuna\ObjectiveWpAdmin\Hooks\Action;
use Creuna\ObjectiveWpAdmin\Pages\Page;
use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Slugify;
use Creuna\ObjectiveWpAdmin\Hooks\Event;

class AddMenuPageAction implements Action
{
    protected $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function event()
    {
        return Event::adminMenu();
    }

    public function call(AdminAdapter $admin, array $args)
    {
        $admin->addMenuPage(
            $this->page->title(),
            $this->page->title(),
            'manage_options',
            Slugify::slug(get_class($this->page)),
            [$this, 'render']
        );
    }

    public function render()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                echo $this->page->get($_GET);
                break;
            case 'POST':
                echo $this->page->post($_POST);
                break;
            default:
                throw new \Exception('Does not support method');
        }
    }
}
