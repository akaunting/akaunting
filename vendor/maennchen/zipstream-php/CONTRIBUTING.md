# ZipStream Readme for Contributors
## Code styling
### Indention
For spaces are used to indent code. The convention is [K&R](http://en.wikipedia.org/wiki/Indent_style#K&R)

### Comments
Double Slashes are used for an one line comment.

Classes, Variables, Methods etc:

```php
/**
 * My comment
 *
 * @myanotation like @param etc.
 */
```

## Pull requests
Feel free to submit pull requests.

## Testing
For every new feature please write a new PHPUnit test.

Before every commit execute `./vendor/bin/phpunit` to check if your changes wrecked something:
