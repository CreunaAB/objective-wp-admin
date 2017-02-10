<?php

namespace Creuna\ObjectiveWpAdmin\Hooks;

class Event
{
    public $name;
    public $arity;

    public function __construct($name, $arity)
    {
        $this->name = $name;
        $this->arity = $arity;
    }

    public static function init()
    {
        return new Event('init', 0);
    }

    public static function adminMenu()
    {
        return new Event('admin_menu', 0);
    }

    public static function adminBarMenu()
    {
        return new Event('admin_bar_menu', 1);
    }

    public static function dashboardSetup()
    {
        return new Event('wp_dashboard_setup', 0);
    }

    public static function editFormAfterEditor()
    {
        return new Event('edit_form_after_editor', 1);
    }

    public static function editorSettings()
    {
        return new Event('wp_editor_settings', 2);
    }

    public static function loaded()
    {
        return new Event('wp_loaded', 0);
    }

    public static function postTypeLink()
    {
        return new Event('post_type_link', 4);
    }

    public static function savePost()
    {
        return new Event('save_post', 3);
    }
}
