<p align="center"><a href="https://crawlerdetect.io/" target="_blank"><img src="https://cloud.githubusercontent.com/assets/340752/23082173/1bd1a396-f550-11e6-8aba-4d3c75edea2f.png" width="321" height="219" /></a><br><br>
<a href="https://crawlerdetect.io/" target="_blank">crawlerdetect.io</a>
<br><br>
</p>
<p align="center">
<a href="https://github.com/JayBizzle/Crawler-Detect/actions"><img alt="GitHub Workflow Status" src="https://img.shields.io/github/workflow/status/JayBizzle/Crawler-Detect/Test?style=flat-square"></a>
<a href="https://packagist.org/packages/jaybizzle/crawler-detect"><img src="https://img.shields.io/packagist/dm/JayBizzle/Crawler-Detect.svg?style=flat-square" /></a>
<a href="https://scrutinizer-ci.com/g/JayBizzle/Crawler-Detect/?branch=master"><img src="https://img.shields.io/scrutinizer/g/JayBizzle/Crawler-Detect.svg?style=flat-square" /></a>
<a href="https://github.com/JayBizzle/Crawler-Detect"><img src="https://img.shields.io/badge/license-MIT-ff69b4.svg?style=flat-square" /></a>
<a href="https://packagist.org/packages/jaybizzle/crawler-detect"><img src="https://img.shields.io/packagist/v/jaybizzle/Crawler-Detect.svg?style=flat-square" /></a>
<a href="https://coveralls.io/github/JayBizzle/Crawler-Detect"><img src="https://img.shields.io/coveralls/JayBizzle/Crawler-Detect/master.svg?style=flat-square" /></a>
</p>

## About CrawlerDetect

CrawlerDetect is a PHP class for detecting bots/crawlers/spiders via the user agent and http_from header. Currently able to detect 1,000's of bots/spiders/crawlers.

### Installation
```
composer require jaybizzle/crawler-detect
```

### Usage
```PHP
use Jaybizzle\CrawlerDetect\CrawlerDetect;

$CrawlerDetect = new CrawlerDetect;

// Check the user agent of the current 'visitor'
if($CrawlerDetect->isCrawler()) {
    // true if crawler user agent detected
}

// Pass a user agent as a string
if($CrawlerDetect->isCrawler('Mozilla/5.0 (compatible; Sosospider/2.0; +http://help.soso.com/webspider.htm)')) {
    // true if crawler user agent detected
}

// Output the name of the bot that matched (if any)
echo $CrawlerDetect->getMatches();
```

### Contributing
If you find a bot/spider/crawler user agent that CrawlerDetect fails to detect, please submit a pull request with the regex pattern added to the `$data` array in `Fixtures/Crawlers.php` and add the failing user agent to `tests/crawlers.txt`.

Failing that, just create an issue with the user agent you have found, and we'll take it from there :)

### Laravel Package
If you would like to use this with Laravel, please see [Laravel-Crawler-Detect](https://github.com/JayBizzle/Laravel-Crawler-Detect)

### Symfony Bundle
To use this library with Symfony 2/3/4, check out the [CrawlerDetectBundle](https://github.com/nicolasmure/CrawlerDetectBundle).

### YII2 Extension
To use this library with the YII2 framework, check out [yii2-crawler-detect](https://github.com/AlikDex/yii2-crawler-detect).

### ES6 Library
To use this library with NodeJS or any ES6 application based, check out [es6-crawler-detect](https://github.com/JefferyHus/es6-crawler-detect).

### Python Library
To use this library in a Python project, check out [crawlerdetect](https://github.com/moskrc/CrawlerDetect).

### .NET Library
To use this library in a .net standard (including .net core) based project, check out [NetCrawlerDetect](https://github.com/gplumb/NetCrawlerDetect).

### Ruby Gem

To use this library with Ruby on Rails or any Ruby-based application, check out [crawler_detect](https://github.com/loadkpi/crawler_detect) gem.

### Go Module

To use this library with Go, check out the [crawlerdetect](https://github.com/x-way/crawlerdetect) module.

_Parts of this class are based on the brilliant [MobileDetect](https://github.com/serbanghita/Mobile-Detect)_

[![Analytics](https://ga-beacon.appspot.com/UA-72430465-1/Crawler-Detect/readme?pixel)](https://github.com/JayBizzle/Crawler-Detect)
