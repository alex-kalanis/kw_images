# kw_images

![Build Status](https://github.com/alex-kalanis/kw_images/actions/workflows/code_checks.yml/badge.svg)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-kalanis/kw_images/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-kalanis/kw_images/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/kw_images/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_images)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/kw_images.svg?v1)](https://packagist.org/packages/alex-kalanis/kw_images)
[![License](https://poser.pugx.org/alex-kalanis/kw_images/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_images)
[![Code Coverage](https://scrutinizer-ci.com/g/alex-kalanis/kw_images/badges/coverage.png?b=master&v=1)](https://scrutinizer-ci.com/g/alex-kalanis/kw_images/?branch=master)

Gallery on (remote) file system tree.

Working with images in tree-like gallery. Usually these libraries uses volume as their
data source, but it is possible to set different (external) storage and use it. Just a
few things cannot be done remotely and these will dump processed content locally and
after changes returns it back to the storage.

## PHP Installation

```bash
composer.phar require alex-kalanis/kw_images
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


## PHP Usage

1.) Use your autoloader (if not already done via Composer autoloader)

2.) Add some external packages with connection to the local or remote services.

3.) Connect the "kalanis\kw_images\FilesHelper" into your app. Extends it for setting your case.

4.) Extend your libraries by interfaces inside the package.

5.) Just call setting and render in controllers


```php
// Somewhere in DI
return function ($params): \kalanis\kw_images\Content\Images {
    return (new \kalanis\kw_images\Access\Factory())->getImages($params);
}
```

```php
// Classes
class ProcessImages
{
    public function __construct(
        // ...
        protected readonly \kalanis\kw_images\Content\Images $images,
        // ...
    ) {}

    public function rotate(string $file, float $angle): bool
    {
        $path = (new \kalanis\kw_paths\ArrayPath())->setString($file);
        return $this->images->rotate($path->getArray(), $angle);
    }
}
```


### Changes

* 1.0 - initial version
* 2.0 - has remote as storage, no volume operation directly here
* 3.0 - has updated paths and following libraries
* 4.0 - has updated code style and all libraries underneath
* 5.0 - uses DateTime, tests with separated classes
