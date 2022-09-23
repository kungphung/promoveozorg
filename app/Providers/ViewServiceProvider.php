<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Statamic\Statamic;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * Add {{ tid }} with path attribute on top of every loaded antlers.html file only once.
     *
     * @return void
     */
    public function boot()
    {
        if (!Statamic::isCpRoute()) {
            View::composer(
                '*',
                function (\Illuminate\View\View $view) {
                    $path = $view->getPath();
                    $filePathBase = strstr($path, 'views') != '' ? strstr($path, 'views') : $path;
                    $filePath = "{{ tid path=\"$filePathBase\" }}\n";

                    $f = fopen($path, 'r');
                    $line = fgets($f);

                    $excluded = [
                        "views/snippets/_button_attributes.antlers.html", // antlers file is loaded within an element tag and will break stuff

                        // templates in vendor folder
                        "views/auth/passwords/email.blade.php",
                        "views/auth/passwords/reset.blade.php",
                        "views/auth/protect/password.antlers.html",
                        "views/partials/head.blade.php",
                        "views/outside.blade.php",
                        "views/partials/scripts.blade.php",
                    ];

                    if ($line != $filePath &&
                        // ignore lines
                        !in_array($filePathBase, $excluded)) {
                        $filePath .= "\r\n".file_get_contents($path);
                        file_put_contents($path, $filePath);
                    }
                }
            );
        }
    }
}
