<?php

namespace Creuna\ObjectiveWpAdmin\Persistence\Fields;

use Creuna\ObjectiveWpAdmin\Persistence\Field;
use Creuna\ObjectiveWpAdmin\Persistence\FieldBase;
use Creuna\ObjectiveWpAdmin\Admin;

class MediaField implements Field
{
    use FieldBase;

    protected $multiple = false;
    protected $defaultValue = '';

    public function view(Admin $admin)
    {
        return new MediaFieldView($this);
    }

    public function multiple()
    {
        $this->multiple = true;
        return $this;
    }

    public function holdsArray()
    {
        return $this->multiple;
    }

    public function serialize($value)
    {
        return json_encode($value);
    }

    public function deserialize($value)
    {
        if (!(
            is_array($value) ||
            is_string($value) &&
            strpos($value, '"') !== 0
        )) {
            $value = json_decode($value);
        }

        if ($this->holdsArray()) {
            return array_map([$this, 'getSrc'], $value);
        }
        if (is_array($value)) {
            if (count($value) === 0) {
                $value = null;
            } else {
                $value = $value[0];
            }
        }
        return $this->getSrc($value);
    }

    private function getSrc($id)
    {
        return function ($size = null) use ($id) {
            if (!isset($size)) {
                return $id;
            }

            // Try to get the downsized version of this image
            $image = image_downsize($id, $size);

            // If a downsized image is found, return it
            if ($image && $image[3]) {
                return $image[0];
            }

            // If not, we need to resize it ourselves

            // Start by getting the file path to the image
            $path = get_attached_file($id);

            // Then get the supported WP_Image_Editor
            $editor = \wp_get_image_editor($path);

            // If image resizing is not available on this machine, return
            // the full image
            if (is_wp_error($editor)) {
                return $image[0];
            }

            // Extract the dimensions
            list($width, $height) = $size;

            // Resize the image
            $editor->resize($width, $height);

            $sizeString = "{$width}x$height";
            $sizedName = basename($path) . "-$sizeString." . pathinfo($path, PATHINFO_EXTENSION);
            $sizedPath = dirname($path) . '/' . $sizedName;
            $uploadDir = wp_upload_dir();

            // Save the resized image to the correct path
            $editor->save($sizedPath);

            // Update the attachment metadata
            $meta = wp_get_attachment_metadata($id);
            $meta['sizes'][$sizeString] = [
                'file' => $sizedName,
                'width' => $width,
                'height' => $height,
            ];
            wp_update_attachment_metadata($id, $meta);

            // Recursively try again
            $getSrc = $this->getSrc($id);
            return $getSrc($size);
        };
    }
}
