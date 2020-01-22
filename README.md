# Kirby 3 imgix plugin [![Version](https://img.shields.io/packagist/v/diesdasdigital/kirby-3-imgix.svg)](https://packagist.org/packages/diesdasdigital/kirby-3-imgix)

If this plugin gets activated, it will serve all images (except for gifs) via [imgix](https://imgix.com/).

## Installation

```bash
composer require diesdasdigital/kirby-3-imgix
```

## Configuration

In any config file or the default `site/config/config.php`:

```php
return [
  'imgix' => true,
  'imgix.domain' => 'https://project-name.imgix.net/',
  'imgix.defaults' => [
    'auto' => 'compress',
  ],
];
```

`imgix.defaults` takes a map of options, which all images will have applied automatically without calling `thumb()`.

## Usage

All images will automatically be served via imgix if `imgix` and `imgix.domain` are set in the config.

The options provided by imgix can be passed via the `thumb()` function:

```php
$page->someImage()->image()->thumb([
  'blur' => '10',
  'con' => '40',
])->url();
```

Since version `1.1.0` support for Kirby’s built-in `srcset()` method is improved to automatically use imgix’s
`w` and `h` parameters instead of width and height.

```php
$page->someImage()->toFile()->srcset([300, 800, 1024])

// with additional parameters
$page->someImage()->toFile()->srcset([
  300 => [
    'width' => 300,
    'crop' => 'top,left'
  ],
  500 => [
    'width' => 500
  ]
])
```

See [imgix API reference](https://docs.imgix.com/apis/url) for all options.

---

Inspired by Kirby’s website, which uses a [custom cloudinary plugin](https://github.com/getkirby/getkirby.com/blob/master/site/plugins/cloudinary/index.php).

Created by [diesdas.digital](https://diesdas.digital)
