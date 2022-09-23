<?php

namespace App\Tags;

use Statamic\Tags\Tags;

class Tid extends Tags
{
    /**
     * Converts the {{ tid }} in an antlers.html file with actual path of file when APP_DEBUG is true.
     *
     * @return string|array
     */
    public function index()
    {
        if (!env('APP_DEBUG')) {
            return;
        }

        if (request()->isJson()) {
            return;
        }

        $path = $this->params->get('path');
        return "<!-- [ $path ] -->";
    }
}
