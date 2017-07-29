# SWIS Text Snippet

Easy and fast way to create a snippet of text, for example for a search result. It will try and hightlight the given words and give you the relevant text around it.

[![Build Status](https://travis-ci.org/swisnl/textsnippet.svg?branch=master)](https://travis-ci.org/swisnl/textsnippet) [![Latest Stable Version](https://poser.pugx.org/swisnl/textsnippet/v/stable)](https://packagist.org/packages/swisnl/textsnippet) [![License](https://poser.pugx.org/swisnl/textsnippet/license)](https://packagist.org/packages/swisnl/textsnippet)

## Examples

Some examples based on a 3 paragraph long Lorum ipsum text.

### Basic usage

```php 
TextSnippet::createSnippet('Lorem', $lorumIpsum);
```

Will result in:


**Lorem** ipsum dolor sit amet, consectetur adipiscing elit. ... Etiam bibendum **lorem** nec tempus sollicitudin. ... Sed in dapibus **lorem**. ... Nunc turpis ipsum, bibendum quis sodales sed, ullamcorper et **lorem**. Donec et metus hendrerit, interdum elit ut, dignissim dui.


### Setting highlight html

You can set the tags surrounding the highlighted text. The `%word%` tag is required.

```php 
TextSnippet::setHighlightTemplate('<strong>%word%</strong>')
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
TextSnippet::setMinWords(10);
TextSnippet::setMaxWords(30);
```

There is a known issue if you set min and max very close to eachother. It might not find the correct set of words/sentences to get exactly between the small gap.