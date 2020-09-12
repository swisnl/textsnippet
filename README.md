# SWIS Text Snippet

Easy and fast way to create a snippet of text, for example for a search result. It will try and highlight the given words and give you the relevant text around it.

[![Build Status](https://travis-ci.org/swisnl/textsnippet.svg?branch=master)](https://travis-ci.org/swisnl/textsnippet) [![Latest Stable Version](https://poser.pugx.org/swisnl/textsnippet/v/stable)](https://packagist.org/packages/swisnl/textsnippet) [![License](https://poser.pugx.org/swisnl/textsnippet/license)](https://packagist.org/packages/swisnl/textsnippet)


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
