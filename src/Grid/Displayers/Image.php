<?php

namespace Dcat\Admin\Grid\Displayers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;
use Dcat\Admin\Support\Helper;

class Image extends AbstractDisplayer
{
    public function display($server = '', $width = 200, $height = 200)
    {
        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }
        $this->value = Helper::array($this->value);
        return collect((array) $this->value)->filter()->map(function ($path) use ($server, $width, $height) {
            if (url()->isValidUrl($path) || mb_strpos($path, 'data:image') === 0) {
                $src = $path;
            } elseif ($server) {
                $src = rtrim($server, '/').'/'.ltrim($path, '/');
            } else {
                $src = Storage::disk(config('admin.upload.disk'))->url($path);
            }

            return "<img data-action='preview-img' src='$src' style='max-width:{$width}px;max-height:{$height}px;cursor:pointer' class='img img-thumbnail' />";
        })->implode('&nbsp;');
    }
}
