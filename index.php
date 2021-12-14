<?php
require(__DIR__ . '/vendor/autoload.php');

use Kirby\Cms\App;
use Kirby\Cms\File;
use Kirby\Cms\FileVersion;
use Imgix\UrlBuilder;

function endsWith($haystack, $needle)
{
    return substr($haystack, -strlen($needle)) === $needle;
}

function imgix($url, $params = [])
{
    $builder = new UrlBuilder(option('imgix.domain'));

    if (option('imgix.secure_token')) {
        $builder->setSignKey(option('imgix.secure_token'));
    }

    if (is_object($url) === true) {
        $url = $url->url();
    }

    // always convert urls to path
    $path = Url::path($url);

    // return the plain url if imgix is deactivated
    if (option('imgix', false) === false or option('imgix.domain', false) === false or endsWith($url, '.gif')) {
        return $url;
    }

    $defaults = option('imgix.defaults', []);

    $params  = array_merge($defaults, $params);
    $options = [];

    $map = [
        'width'   => 'w',
        'height'  => 'h',
    ];

    foreach ($params as $key => $value) {
        if (isset($map[$key]) && !empty($value)) {
            $options[$map[$key]] = $value;
        } elseif (!isset($map[$key]) && !empty($value)) {
            $options[$key] = $value;
        }
    }

    return $builder->createURL($path, $params);
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
