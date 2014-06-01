# Aura.Asset

Asset management for [Aura.Web_Project](https://github.com/auraphp/Aura.Web_Project) package.

## Foreword

### Requirements

This package requires PHP 5.4 or later. Unlike Aura library packages, this 
asset package has userland dependencies, which themselves may have other
dependencies:

- [aura/web-kernel](https://packagist.org/packages/aura/web-kernel)

### Installation

This asset is installable and autoloadable via Composer with the following
`require` element in your `composer.json` file:

    "require": {
        "aura/asset": "2.*@dev"
    }

### Tests

Want to write some tests, before that discuss with Paul M Jones his thoughts.

### PSR Compliance

This kernel attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

### Community

To ask questions, provide feedback, or otherwise communicate with the Aura community, please join our [Google Group](http://groups.google.com/group/auraphp), follow [@auraphp on Twitter](http://twitter.com/auraphp), or chat with us on #auraphp on Freenode.

## Structure of Package

Assume you have a `Vendor.Package`. All your assets should be in the 
`web` folder. The folder names `css`, `images`, `js` can be according to your loveable names.


```bash
├── src
│   ├── Cli
│   └── Web
├── tests
└── web
    ├── css
    │   └── some.css
    ├── images
    │   ├── another.jpg
    │   └── some.png
    └── js
        └── hello.js
```

Assuming you have the same structure, now in your template you can point 
to `/asset/vendor/package/css/some.css`, `/asset/vendor/package/js/hello.js`, `/asset/vendor/package/images/another.jpg`.

Onething you still need to make sure in the name `asset/vendor/package` 

> `vendor/package` which is the composer package name.
