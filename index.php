<?php

use Kirby\Cms\App;
use Kirby\Cms\File;
use Kirby\Cms\FileVersion;

function imgix($url, $params = [])
{
    if (is_object($url) === true) {
        $url = $url->url();
    }

    // always convert urls to path
    $url = Url::path($url);

    // return the plain url if imgix is deactivated
    if (option('imgix', false) === false or option('imgix.domain', false) === false) {
        return $url;
    }

    $defaults = option('imgix.defaults', []);

    $params  = array_merge($defaults, $params);
    $options = [];

    foreach ($params as $key => $value) {
        if (!empty($value)) {
            $options[] = $key . '=' . $value;
        }
    }

    $options = implode('&', $options);

    return option('imgix.domain') . $url . '?' . $options;
}

Kirby::plugin('diesdasdigital/imgix', [
    'components' => [
        'file::version' => function (App $kirby, File $file, array $options = []) {

            static $originalComponent;

            if (option('imgix', false) !== false) {
                $url = imgix($file->mediaUrl(), $options);

                return new FileVersion([
                    'modifications' => $options,
                    'original'      => $file,
                    'root'          => $file->root(),
                    'url'           => $url,
                ]);
            }

            if ($originalComponent === null) {
                $originalComponent = (require $kirby->root('kirby') . '/config/components.php')['file::version'];
            }

            return $originalComponent($kirby, $file, $options);
        },

        'file::url' => function (App $kirby, File $file): string {

            static $originalComponent;

            if (option('imgix', false) !== false) {
                if ($file->type() === 'image') {
                    return imgix($file->mediaUrl());
                }
                return $file->mediaUrl();
            }

            if ($originalComponent === null) {
                $originalComponent = (require $kirby->root('kirby') . '/config/components.php')['file::url'];
            }

            return $originalComponent($kirby, $file);
        }
    ]
]);
