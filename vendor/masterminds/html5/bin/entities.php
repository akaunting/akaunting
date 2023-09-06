<?php
/**
 * Fetch the entities.json file and convert to PHP datastructure.
 */

// The URL to the official entities JSON file.
$ENTITIES_URL = 'http://www.w3.org/TR/2012/CR-html5-20121217/entities.json';

$payload = file_get_contents($ENTITIES_URL);
$json = json_decode($payload);

$table = array();
foreach ($json as $name => $obj) {
    $sname = substr($name, 1, -1);
    $table[$sname] = $obj->characters;
}

echo '<?php
namespace Masterminds\\HTML5;
/** Entity lookup tables. This class is automatically generated. */
class Entities {
  public static $byName = ';
var_export($table);
echo ';
}' . PHP_EOL;
//print serialize($table);
