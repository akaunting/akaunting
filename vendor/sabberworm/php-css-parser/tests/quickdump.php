#!/usr/bin/env php
<?php

require_once(dirname(__FILE__).'/bootstrap.php');

$sSource = file_get_contents('php://stdin');
$oParser = new Sabberworm\CSS\Parser($sSource);

$oDoc = $oParser->parse();
echo "\n".'#### Input'."\n\n```css\n";
print $sSource;

echo "\n```\n\n".'#### Structure (`var_dump()`)'."\n\n```php\n";
var_dump($oDoc);

echo "\n```\n\n".'#### Output (`render()`)'."\n\n```css\n";
print $oDoc->render();

echo "\n```\n";

