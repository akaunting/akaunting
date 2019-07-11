# Akaunting™

 ![Latest Stable Version](https://img.shields.io/github/release/akaunting/akaunting.svg) ![Total Downloads](https://img.shields.io/github/downloads/akaunting/akaunting/total.svg) [![Crowdin](https://d322cqt584bo4o.cloudfront.net/akaunting/localized.svg)](https://crowdin.com/project/akaunting) ![Build Status](https://travis-ci.com/akaunting/akaunting.svg) [![Backers on Open Collective](https://opencollective.com/akaunting/backers/badge.svg)](#backers) [![Sponsors on Open Collective](https://opencollective.com/akaunting/sponsors/badge.svg)](#sponsors)

Akaunting is a free, open source and online accounting software designed for small businesses and freelancers. It is built with modern technologies such as Laravel, Bootstrap, jQuery, RESTful API etc. Thanks to its modular structure, Akaunting provides an awesome App Store for users and developers.

* [Home](https://akaunting.com) - The house of Akaunting
* [Blog](https://akaunting.com/blog) - Get the latest news
* [Forum](https://akaunting.com/forum) - Ask for support
* [Documentation](https://akaunting.com/docs) - Learn how to use
* [Translations](https://crowdin.com/project/akaunting) - Akaunting in your language

## Requirements

* PHP 5.6.4 or higher
* Database (eg: MySQL, PostgreSQL, SQLite)
* Web Server (eg: Apache, Nginx, IIS)
* [Other libraries](https://akaunting.com/docs/requirements)

## Framework

Akaunting uses [Laravel](http://laravel.com), the best existing PHP framework, as the foundation framework and [Modules](https://nwidart.com/laravel-modules) package for Apps.

## Installation

  * Install [Composer](https://getcomposer.org/download)
  * Download the [repository](https://github.com/akaunting/akaunting/archive/master.zip) and unzip into your server
  * Open and point your command line to the directory you unzipped Akaunting
  * Run the following command: `composer install`
  * Finally, launch the [installer](https://akaunting.com/docs/installation)

## Docker

It is possible to containerise Akaunting using the [`docker-compose`](docker-compose.yml) file. Here are a few commands:

```
# Build the app
docker build -t akaunting .

# Run the app
docker-compose up -d

# Make sure you the dependencies are installed
docker-compose exec web composer install

# Stream logs
docker-compose logs -f web

# Access the container
docker-compose exec web /bin/sh

# Stop & Delete everything
docker-compose down -v
```

## Contributing

Fork the repository, make the code changes then submit a pull request.

Please, be very clear on your commit messages and pull requests, empty pull request messages may be rejected without reason.

When contributing code to Akaunting, you must follow the PSR coding standards. The golden rule is: Imitate the existing Akaunting code.

Please note that this project is released with a [Contributor Code of Conduct](https://akaunting.com/conduct). By participating in this project you agree to abide by its terms.

## Translation

If you'd like to contribute translations, please check out our [Crowdin](https://crowdin.com/project/akaunting) project.

## Changelog

Please see [Releases](../../releases) for more information what has changed recently.

## Security

If you discover any security related issues, please email security@akaunting.com instead of using the issue tracker.

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [Cüneyt Şentürk](https://github.com/cuneytsenturk)
- [All Contributors](../../contributors)

## Contributors

This project exists thanks to all the people who contribute. [[Contribute](CONTRIBUTING.md)].
[![Contributors](https://opencollective.com/akaunting/contributors.svg?width=890&button=false)](../../contributors)

## Backers

Thank you to all our backers! 🙏 [[Become a backer](https://opencollective.com/akaunting#backer)]

[![Backers](https://opencollective.com/akaunting/backers.svg?width=890)](https://opencollective.com/akaunting#backers)

## Sponsors

Support this project by becoming a sponsor. Your logo will show up here with a link to your website. [[Become a sponsor](https://opencollective.com/akaunting#sponsor)]

[![Sponsor 0](https://opencollective.com/akaunting/sponsor/0/avatar.svg)](https://opencollective.com/akaunting/sponsor/0/website)
[![Sponsor 1](https://opencollective.com/akaunting/sponsor/1/avatar.svg)](https://opencollective.com/akaunting/sponsor/1/website)
[![Sponsor 2](https://opencollective.com/akaunting/sponsor/2/avatar.svg)](https://opencollective.com/akaunting/sponsor/2/website)
[![Sponsor 3](https://opencollective.com/akaunting/sponsor/3/avatar.svg)](https://opencollective.com/akaunting/sponsor/3/website)
[![Sponsor 4](https://opencollective.com/akaunting/sponsor/4/avatar.svg)](https://opencollective.com/akaunting/sponsor/4/website)
[![Sponsor 5](https://opencollective.com/akaunting/sponsor/5/avatar.svg)](https://opencollective.com/akaunting/sponsor/5/website)
[![Sponsor 6](https://opencollective.com/akaunting/sponsor/6/avatar.svg)](https://opencollective.com/akaunting/sponsor/6/website)
[![Sponsor 7](https://opencollective.com/akaunting/sponsor/7/avatar.svg)](https://opencollective.com/akaunting/sponsor/7/website)
[![Sponsor 8](https://opencollective.com/akaunting/sponsor/8/avatar.svg)](https://opencollective.com/akaunting/sponsor/8/website)
[![Sponsor 9](https://opencollective.com/akaunting/sponsor/9/avatar.svg)](https://opencollective.com/akaunting/sponsor/9/website)

## License

Akaunting is released under the [GPLv3 license](LICENSE.txt).
