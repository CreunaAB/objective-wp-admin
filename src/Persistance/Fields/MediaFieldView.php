<?php

namespace Creuna\ObjectiveWpAdmin\Persistance\Fields;

use Creuna\ObjectiveWpAdmin\AdminAdapter;
use Creuna\ObjectiveWpAdmin\Persistance\FieldView;

class MediaFieldView implements FieldView
{
    protected $field;

    public function __construct(MediaField $field)
    {
        $this->field = $field;
    }

    public function render($value)
    {
        global $post;

        if (!is_array($value)) {
            $value = $value === '' ? [] : [$value];
        }

        $items = array_map(function ($id) {
            return (object) [
                'id' => $id,
                'url' => $this->src($id),
            ];
        }, $value);

        $fieldId = "field_{$this->field->name()}";
        $data = htmlspecialchars(json_encode($items));

        $buttonLabel = $this->field->holdsArray() ? 'Add' : 'Choose';
        return "
            <tr>
                <th scope='row'>
                    <label for='{$fieldId}_button'>
                        {$this->field->title()}
                    </label>
                </th>
                <td>
                    <div id='$fieldId' data-items='$data'></div>
                </td>
            </tr>
        ";
    }

    private function src($id)
    {
        $result = wp_get_attachment_image_src($id, 'medium');
        if (!is_array($result)) {
            return '';
        }
        return $result[0];
    }

    public function parseValue($value)
    {
        if ($value === '') {
            return [];
        }
        return explode(',', $value);
    }

    public function assets(AdminAdapter $adapter)
    {
        // $adapter->enqueueScript('media-upload');
        // $adapter->enqueueScript('thickbox');
        // $adapter->enqueueStyle('thickbox');
        \wp_enqueue_media();

        $adapter->action('admin_print_footer_scripts', function () {
            $fieldId = "field_{$this->field->name()}";
            $multiple = $this->field->holdsArray() ? 'true' : 'false';

            echo "
                <script>
                    jQuery(function ($) {
                        var frame;
                        var container = $('#{$fieldId}');
                        var items = JSON.parse(container.attr('data-items'));
                        var multiple = $multiple;

                        function onClick (event) {
                            event.preventDefault();

                            // If the media frame already exists, reopen it.
                            if (frame) {
                                frame.open();
                                return;
                            }

                            frame = wp.media({
                                title: 'Media Browser',
                                button: {
                                    text: 'Use this media'
                                },
                                multiple: multiple
                            });

                            frame.on('select', function() {
                                var attachments = frame.state().get('selection').toJSON()
                                    .map(function (item) {
                                        return {
                                            id: item.id,
                                            url: items.sizes && item.sizes.medium
                                                ? item.sizes.medium.url : item.url
                                        };
                                    });

                                if (multiple) {
                                    items = items.concat(attachments);
                                } else {
                                    items = attachments;
                                }

                                render();
                            });

                            frame.open();
                        }

                        function render () {
                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'custom_{$this->field->name()}';
                            input.value = items.map(function (item) {
                                return item.id;
                            }).join(',');

                            var addButton = document.createElement('button');
                            addButton.onclick = onClick;
                            addButton.className = 'button button-large';
                            addButton.textContent = multiple ? 'Add media' : 'Select media';

                            var images = items.map(function (item) {
                                var li = document.createElement('li');
                                li.style.display = 'inline-block';
                                li.style.width = '20%';

                                var img = document.createElement('img');
                                img.src = item.url;
                                img.style.width = '100%';

                                var deleteButton = document.createElement('button');
                                deleteButton.innerHTML = '<span class=media-modal-icon><span class=screen-reader-text>Remove image</span></span>';
                                deleteButton.className = 'button-link media-modal-close';
                                deleteButton.style.float = 'right';
                                deleteButton.style.position = 'relative';
                                deleteButton.onclick = function (event) {
                                    event.preventDefault();
                                    items = items.filter(function (e) {
                                        return e.id !== item.id;
                                    });
                                    render();
                                };

                                li.appendChild(deleteButton);
                                li.appendChild(img);

                                return li;
                            });

                            var list = document.createElement('ul');
                            images.forEach(function (img) {
                                list.appendChild(img);
                            });

                            container.empty();

                            container.append(addButton);
                            container.append(input);
                            container.append(list);
                        }

                        render();

                    });
                </script>
            ";
        }, 1000);
    }
}
