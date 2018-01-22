<?php

namespace Creuna\ObjectiveWpAdmin\Persistence\Fields;

class Editor
{
    protected $settings;
    protected $stylesheets;
    protected $toolbars;
    protected $styles;

    public function __construct(
        array $settings = null,
        array $stylesheets = [],
        array $toolbars = [],
        array $styles = []
    ) {
        $this->settings = isset($settings) ? $settings : [
            'wpautop' => true,
            'media_buttons' => false,
            'tabindex' => null,
            'editor_css' => null,
            'editor_class' => '',
            'editor_height' => null,
            'teeny' => false,
            'dfw' => false,
            'quicktags' => false,
            'drag_drop_upload' => false,
        ];
        $this->stylesheets = $stylesheets;
        $this->toolbars = $toolbars;
        $this->styles = $styles;
    }

    public function config()
    {
        $tinymce = [
            'theme' => 'modern',
            'skin' => 'lightgray',
            'language' => 'en',
            'formats' => json_encode([
                // 'alignleft' => [
                //     [
                //         'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li',
                //         'styles' => ['textAlign' => 'left']
                //     ],
                //     [
                //         'selector' => 'img,table,dl.wp-caption',
                //         'classes' => 'alignleft'
                //     ],
                // ],
                // 'aligncenter' => [
                //     [
                //         'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li',
                //         'styles' => ['textAlign' => 'center']
                //     ],
                //     [
                //         'selector' => 'img,table,dl.wp-caption',
                //         'classes' => 'aligncenter'
                //     ],
                // ],
                // 'alignright' => [
                //     [
                //         'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li',
                //         'styles' => ['textAlign' => 'right']
                //     ],
                //     [
                //         'selector' => 'img,table,dl.wp-caption',
                //         'classes' => 'alignright'
                //     ],
                // ],
                // 'strikethrough' => ['inline' => 'del']
            ]),
            'relative_urls' => false,
            'remove_script_host' => false,
            'convert_urls' => false,
            'browser_spellcheck' => true,
            'fix_list_elements' => true,
            'entities' => '38,amp,60,lt,62,gt',
            'entity_encoding' => 'raw',
            'keep_styles' => false,
            // 'cache_suffix' => 'wp-mce-4308-20160323',
            'preview_styles' => 'font-family font-size font-weight font-style text-decoration text-transform',
            'end_container_on_empty_block' => true,
            'wpeditimage_disable_captions' => false,
            'wpeditimage_html5_captions' => false,
            'plugins' => 'charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview',
            'wp_lang_attr' => 'en-US',
            // 'content_css' => 'wp/wp-includes/css/dashicons.min.css?ver=4.5,wp/wp-includes/js/tinymce/skins/wordpress/wp-content.css?ver=4.5',
            // 'selector' => '#content',
            'resize' => false,
            'menubar' => false,
            'wpautop' => true,
            'indent' => false,
            'toolbar1' => 'noexist', //'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,dfw,wp_adv',
            'toolbar2' => '', //'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
            'toolbar3' => '',
            'toolbar4' => '',
            'tabfocus_elements' => 'content-html,save-post',
            'body_class' => 'content post-type-admin_types_blogpost post-status-publish locale-en-us',
            'wp_autoresize_on' => true,
            'add_unload_trigger' => false
        ];

        if (count($this->stylesheets) > 0) {
            $tinymce['content_css'] = implode(',', $this->stylesheets);
        }

        foreach ($this->toolbars as $i => $toolbar) {
            $num = $i + 1;
            $tinymce["toolbar$num"] = $toolbar->config();
        }

        if (count($this->styles) > 0) {
            $tinymce['style_formats'] = json_encode($this->styles);
        }

        $settings = $this->settings;
        $settings['tinymce'] = $tinymce;
        return $settings;
    }

    public function stylesheet($url)
    {
        $styles = $this->stylesheets;
        $styles[] = $url;
        return new static(
            $this->settings,
            $styles,
            $this->toolbars,
            $this->styles
        );
    }

    public function toolbars($toolbar1 = null, $toolbar2 = null, $toolbar3 = null, $toolbar4 = null)
    {
        $toolbars = [];
        if (isset($toolbar1)) {
            $toolbars[] = $toolbar1;
        }
        if (isset($toolbar2)) {
            $toolbars[] = $toolbar2;
        }
        if (isset($toolbar3)) {
            $toolbars[] = $toolbar3;
        }
        if (isset($toolbar4)) {
            $toolbars[] = $toolbar4;
        }
        if (count($toolbars) > 1) {
            $toolbars[0] = $toolbar1->add('wp_adv');
        }
        return new static(
            $this->settings,
            $this->stylesheets,
            $toolbars,
            $this->styles
        );
    }

    public static function toolbar()
    {
        return new EditorToolbar();
    }

    public static function make()
    {
        return new static();
    }

    public function format($title, $style)
    {
        if (is_string($style)) {
            $style = $this->shorthandStyle($style);
        }
        $style['title'] = $title;
        $styles = $this->styles;
        $styles[] = $style;
        return new static(
            $this->settings,
            $this->stylesheets,
            $this->toolbars,
            $styles
        );
    }

    private function shorthandStyle($style)
    {
        $segments = explode('.', $style);
        $block = array_shift($segments);
        $classes = implode(' ', $segments);
        return compact('block', 'classes');
    }

    public function media()
    {
        $settings = $this->settings;
        $settings['media_buttons'] = true;
        $settings['drag_drop_upload'] = true;
        return new static(
            $settings,
            $this->stylesheets,
            $this->toolbars,
            $this->styles
        );
    }

    public function html()
    {
        $settings = $this->settings;
        $settings['quicktags'] = true;
        return new static(
            $settings,
            $this->stylesheets,
            $this->toolbars,
            $this->styles
        );
    }
}
