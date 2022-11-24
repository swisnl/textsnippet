# SWIS Text Snippet

Easy and fast way to create a snippet of text, for example for a search result. It will try and highlight the given words and give you the relevant text around it.

[![Build Status](https://img.shields.io/travis/swisnl/textsnippet/master.svg)](https://travis-ci.org/swisnl/textsnippet)
[![Latest Stable Version](https://img.shields.io/packagist/v/swisnl/textsnippet.svg)](https://packagist.org/packages/swisnl/textsnippet)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://packagist.org/packages/swisnl/textsnippet)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen.svg)](https://plant.treeware.earth/swisnl/textsnippet)


## Installation

Just use composer to install the package. Or download and include the `TextSnipet.php` file.

``composer require swisnl/textsnippet``


## Examples

Some examples based on a 3 paragraph long Lorum ipsum text.

### Basic usage

```php
$snippet = new Swis\TextSnippet\TextSnippet();
$snippet->createSnippet('Lorem', $lorumIpsum);
```

Will result in:


**Lorem** ipsum dolor sit amet, consectetur adipiscing elit. ... Etiam bibendum **lorem** nec tempus sollicitudin. ... Sed in dapibus **lorem**. ... Nunc turpis ipsum, bibendum quis sodales sed, ullamcorper et **lorem**. Donec et metus hendrerit, interdum elit ut, dignissim dui.


### For Laravel

Add the source location to ``composer.json`` which adds the package to the composer autoload collection:
```
"autoload": {
    "psr-4": {
        "App\\": "app/", 
        "Swis\\TextSnippet\\": "vendor/swisnl/textsnippet/src/"
    }
}
```
And run ``composer dump-autoload``


### Setting highlight html

You can set the tags surrounding the highlighted text. The `%word%` tag is required.

```php
$snippet = new Swis\TextSnippet\TextSnippet();
$snippet->setHighlightTemplate('<strong>%word%</strong>');
```

### Setting min and max words

Setting min and maxwords tells the class to try and keep the number of words between the min and max.

```php
// Defaults
$minWords = 30;
$maxWords = 100;
```

Setting min and max words.

```php
$snippet = new Swis\TextSnippet\TextSnippet();
$snippet->setMinWords(10);
$snippet->setMaxWords(30);
```

There is a known issue if you set min and max very close to eachother. It might not find the correct set of words/sentences to get exactly between the small gap.

## Licence

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**](https://plant.treeware.earth/swisnl/textsnippet) to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.

## SWIS :heart: Open Source

[SWIS](https://www.swis.nl) is a web agency from Leiden, the Netherlands. We love working with open source software.
