# kw_images

[![Build Status](https://travis-ci.org/alex-kalanis/kw_images.svg?branch=master)](https://travis-ci.org/alex-kalanis/kw_images)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-kalanis/kw_images/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-kalanis/kw_images/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/kw_images/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_images)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/kw_images.svg?v1)](https://packagist.org/packages/alex-kalanis/kw_images)
[![License](https://poser.pugx.org/alex-kalanis/kw_images/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/kw_images)
[![Code Coverage](https://scrutinizer-ci.com/g/alex-kalanis/kw_images/badges/coverage.png?b=master&v=1)](https://scrutinizer-ci.com/g/alex-kalanis/kw_images/?branch=master)

Working with images on system in dir tree. Usually these libraries uses volume as their
data source, but it is possible to set different (external) storage and use it. Just a
few things cannot be done remotely and these will dump processed content locally and
after changes returns it back to storage.

## PHP Installation

```
{
    "require": {
        "alex-kalanis/kw_images": "2.0"
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


## PHP Usage

1.) Use your autoloader (if not already done via Composer autoloader)

2.) Add some external packages with connection to the local or remote services.

3.) Connect the "kalanis\kw_images\FilesHelper" into your app. Extends it for setting your case.

4.) Extend your libraries by interfaces inside the package.

5.) Just call setting and render
