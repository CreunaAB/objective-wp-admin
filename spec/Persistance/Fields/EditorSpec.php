<?php

namespace spec\Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\Persistance\Fields\Editor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Creuna\ObjectiveWpAdmin\Persistance\Fields\EditorToolbar;
use Creuna\ObjectiveWpAdmin\Util\DynamicObject;

class EditorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Editor::class);
    }

    function it_builds_a_tinymce_config()
    {
        $this->config()->shouldBeLike([
            'wpautop' => true,
            'media_buttons' => false,
            'tabindex' => null,
            'editor_css' => null,
            'editor_class' => '',
            'editor_height' => null,
            'teeny' => false,
            'dfw' => false,
            'tinymce' => [
                'theme' => 'modern',
                'skin' => 'lightgray',
                'language' => 'en',
                'formats' => '[]',
                'relative_urls' => false,
                'remove_script_host' => false,
                'convert_urls' => false,
                'browser_spellcheck' => true,
                'fix_list_elements' => true,
                'entities' => '38,amp,60,lt,62,gt',
                'entity_encoding' => 'raw',
                'keep_styles' => false,
                'preview_styles' => 'font-family font-size font-weight font-style text-decoration text-transform',
                'end_container_on_empty_block' => true,
                'wpeditimage_disable_captions' => false,
                'wpeditimage_html5_captions' => false,
                'plugins' => 'charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview,wpembed',
                'wp_lang_attr' => 'en-US',
                'resize' => false,
                'menubar' => false,
                'wpautop' => true,
                'indent' => false,
                'toolbar1' => 'noexist',
                'toolbar2' => '',
                'toolbar3' => '',
                'toolbar4' => '',
                'tabfocus_elements' => 'content-html,save-post',
                'body_class' => 'content post-type-admin_types_blogpost post-status-publish locale-en-us',
                'wp_autoresize_on' => true,
                'add_unload_trigger' => false
            ],
            'quicktags' => false,
            'drag_drop_upload' => false,
        ]);
    }

    function it_can_choose_a_css_style_for_the_content()
    {
        $this->stylesheet('/my-style.css')->config()
            ->shouldFind('tinymce.content_css', '/my-style.css');
    }

    function it_can_choose_multiple_stylesheets()
    {
        $this
            ->stylesheet('/my-style.css')
            ->stylesheet('/my-style2.css')
            ->config()
            ->shouldFind('tinymce.content_css', '/my-style.css,/my-style2.css');
    }

    function it_can_have_a_toolbar(EditorToolbar $toolbar)
    {
        $toolbar->config()->willReturn('bold,italic');

        $this->toolbars($toolbar)
            ->config()
            ->shouldFind('tinymce.toolbar1', 'bold,italic');
    }

    function it_can_have_up_to_four_toolbars(EditorToolbar $toolbar)
    {
        $toolbar->config()->willReturn('bold,italic');
        $toolbar->add('wp_adv')->willReturn(new DynamicObject([
            'config' => function () {
                return 'bold,italic,wp_adv';
            }
        ]));

        $config = $this->toolbars($toolbar, $toolbar, $toolbar, $toolbar)->config();

        $config->shouldFind('tinymce.toolbar1', 'bold,italic,wp_adv');
        $config->shouldFind('tinymce.toolbar2', 'bold,italic');
        $config->shouldFind('tinymce.toolbar3', 'bold,italic');
        $config->shouldFind('tinymce.toolbar4', 'bold,italic');
    }

    function it_can_add_a_format()
    {
        $this->format('My Style', [
            'block' => 'h1',
            'classes' => 'some-class'
        ])->config()->shouldFind('tinymce.style_formats', json_encode([
            [
                'block' => 'h1',
                'classes' => 'some-class',
                'title' => 'My Style',
            ],
        ]));
    }

    function it_can_add_a_style_with_a_shorthand_syntax()
    {
        $this->format('My Style', 'h1.some-class.some-other')
            ->config()->shouldFind('tinymce.style_formats', json_encode([
                [
                    'block' => 'h1',
                    'classes' => 'some-class some-other',
                    'title' => 'My Style',
                ],
            ]));
    }

    function it_can_add_media_upload_functionality()
    {
        $config = $this->media()->config();
        $config->shouldFind('media_buttons', true);
        $config->shouldFind('drag_drop_upload', true);
    }

    function it_can_add_html_tab_functionality()
    {
        $this->html()
            ->config()->shouldFind('quicktags', true);
    }

    public function getMatchers()
    {
        return [
            'find' => function ($subject, $path, $value) {
                $segments = explode('.', $path);
                $subject = array_reduce($segments, function ($subject, $segment) {
                    return $subject[$segment];
                }, $subject);
                return $subject == $value;
            },
        ];
    }
}
