# Kirby 3 imgix plugin

If this plugin gets activated, it will serve all images via [imgix](https://imgix.com/).

## Installation

```bash
composer require diesdasdigital/kirby-3-imgix
```

## Configuration

In any config file or the default `site/config/config.php`:
```php
return [
  'imgix' => true,
  'imgix.domain' => 'https://project-name.imgix.net',
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

See [imgix API reference](https://docs.imgix.com/apis/url) for all options.

---

Inspired by Kirby’s website, which uses a [custom cloudinary plugin](https://github.com/getkirby/getkirby.com/blob/master/site/plugins/cloudinary/index.php).

Created by [diesdas.digital](https://diesdas.digital)
