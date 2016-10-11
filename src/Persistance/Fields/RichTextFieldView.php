<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistance\FieldView;

class RichTextFieldView implements FieldView
{
    protected $field;

    public function __construct(RichTextField $field)
    {
        $this->field = $field;
    }

    public function render($value)
    {
        return "
            <tr>
                <th scope='row' colspan='2'>
                    <div>
                        <label for='field_{$this->field->name()}'>
                            {$this->field->title()}
                        </label>
                    </div>
                    <textarea
                        style='width: 100%'
                        class='regular-text objective-wp-admin__add-editor'
                        id='field_{$this->field->name()}'
                        name='custom_{$this->field->name()}'
                    >$value</textarea>
                </th>
            </tr>
        ";
    }

    public function parseValue($value)
    {
        return $value;
    }

    public function assets(AdminAdapter $adapter)
    {
        $adapter->enqueueScript('media-upload');
        $adapter->enqueueScript('thickbox');

        $adapter->enqueueStyle('thickbox');

        $adapter->action('admin_print_footer_scripts', function () {
            echo "
                <script>
                    jQuery('textarea.objective-wp-admin__add-editor').each(function () {
                        tinyMCE.execCommand('mceAddEditor', false, this.id);
                    });
                </script>
            ";
        }, 1000);
    }
}
