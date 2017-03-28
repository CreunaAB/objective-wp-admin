<?php

namespace Creuna\ObjectiveWpAdmin\Pages;

interface Page
{
    public function title();
    public function get($query);
    public function post($form);
}
