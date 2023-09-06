# PHPLOC

> **Warning**   
> This is a fork of [phploc/phploc](https://github.com/sebastianbergmann/phploc) not intended for use in real projects. I've forked the package to make [`wnx/laravel-stats`](https://github.com/stefanzweifel/laravel-stats) compatible with different Laravel version. (I made sure multiple versions of `sebastian/version` are supported.)

---

`phploc` is a tool for quickly measuring the size and analyzing the structure of a PHP project.

## Installation

This tool is distributed as a [PHP Archive (PHAR)](https://php.net/phar):

```bash
$ wget https://phar.phpunit.de/phploc.phar

$ php phploc.phar --version
```

Using [Phive](https://phar.io/) is the recommended way for managing the tool dependencies of your project:

```bash
$ phive install phploc

$ ./tools/phploc --version
```

**[It is not recommended to use Composer to download and install this tool.](https://twitter.com/s_bergmann/status/999635212723212288)**

## Usage Examples

### Analyse a directory and print the result

```
$ php phploc.phar src
phploc 7.0.0 by Sebastian Bergmann.

Directories                                          3
Files                                               10

Size
  Lines of Code (LOC)                             1882
  Comment Lines of Code (CLOC)                     255 (13.55%)
  Non-Comment Lines of Code (NCLOC)               1627 (86.45%)
  Logical Lines of Code (LLOC)                     377 (20.03%)
    Classes                                        351 (93.10%)
      Average Class Length                          35
        Minimum Class Length                         0
        Maximum Class Length                       172
      Average Method Length                          2
        Minimum Method Length                        1
        Maximum Method Length                      117
    Functions                                        0 (0.00%)
      Average Function Length                        0
    Not in classes or functions                     26 (6.90%)

Cyclomatic Complexity
  Average Complexity per LLOC                     0.49
  Average Complexity per Class                   19.60
    Minimum Class Complexity                      1.00
    Maximum Class Complexity                    139.00
  Average Complexity per Method                   2.43
    Minimum Method Complexity                     1.00
    Maximum Method Complexity                    96.00

Dependencies
  Global Accesses                                    0
    Global Constants                                 0 (0.00%)
    Global Variables                                 0 (0.00%)
    Super-Global Variables                           0 (0.00%)
  Attribute Accesses                                85
    Non-Static                                      85 (100.00%)
    Static                                           0 (0.00%)
  Method Calls                                     280
    Non-Static                                     276 (98.57%)
    Static                                           4 (1.43%)

Structure
  Namespaces                                         3
  Interfaces                                         1
  Traits                                             0
  Classes                                            9
    Abstract Classes                                 0 (0.00%)
    Concrete Classes                                 9 (100.00%)
  Methods                                          130
    Scope
      Non-Static Methods                           130 (100.00%)
      Static Methods                                 0 (0.00%)
    Visibility
      Public Methods                               103 (79.23%)
      Non-Public Methods                            27 (20.77%)
  Functions                                          0
    Named Functions                                  0 (0.00%)
    Anonymous Functions                              0 (0.00%)
  Constants                                          0
    Global Constants                                 0 (0.00%)
    Class Constants                                  0 (0.00%)
```
