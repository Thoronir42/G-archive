<?php

namespace Core\Traits;


trait LinkMaking
{
    public function createLink($target, $caption = null, $icon = null, $parameters = [])
    {
        if (!$caption) {
            $caption = $target;
        }
        return [
            'target' => $target,
            'caption' => $caption,
            'icon' => $icon,
            'parameters' => $parameters,
        ];
    }
}
