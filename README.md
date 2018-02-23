# Akaunting™

 ![Latest Stable Version](https://img.shields.io/github/release/akaunting/akaunting.svg) ![Total Downloads](https://img.shields.io/github/downloads/akaunting/akaunting/total.svg) [![Crowdin](https://d322cqt584bo4o.cloudfront.net/akaunting/localized.svg)](https://crowdin.com/project/akaunting) [![Backers on Open Collective](https://opencollective.com/akaunting/backers/badge.svg)](#backers) [![Sponsors on Open Collective](https://opencollective.com/akaunting/sponsors/badge.svg)](#sponsors)

Akaunting is a free, open source and online accounting software designed for small businesses and freelancers. It is built with modern technologies such as Laravel, Bootstrap, jQuery, RESTful API etc. Thanks to its modular structure, Akaunting provides an awesome App Store for users and developers.

* [Home](https://akaunting.com) - The house of Akaunting
* [Blog](https://akaunting.com/blog) - Get the latest news
* [Forum](https://akaunting.com/forum) - Join the community
* [Documentation](https://akaunting.com/docs) - Learn more about Akaunting

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

It is possible to containerise Akaunting using the [`docker-compose`](docker/docker-compose.build.yaml) file. Here are a few commands:

```
# Make sure you the dependencies are installed
composer install

# Build the app
docker-compose -f docker/docker-compose.build.yaml build

# Run the app
docker-compose up

# Access the container
docker exec -it CONTAINER_ID /bin/sh
```

## docker-compose examples
In the `docker/` folder you'll find some example file to run the image with several databases. 

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
<a href="graphs/contributors"><img src="https://opencollective.com/akaunting/contributors.svg?width=890&button=false" /></a>


## Backers

Thank you to all our backers! 🙏 [[Become a backer](https://opencollective.com/akaunting#backer)]

<a href="https://opencollective.com/akaunting#backers" target="_blank"><img src="https://opencollective.com/akaunting/backers.svg?width=890"></a>


## Sponsors

Support this project by becoming a sponsor. Your logo will show up here with a link to your website. [[Become a sponsor](https://opencollective.com/akaunting#sponsor)]

<a href="https://opencollective.com/akaunting/sponsor/0/website" target="_blank"><img src="https://opencollective.com/akaunting/sponsor/0/avatar.svg"></a>
<a href="https://opencollective.com/akaunting/sponsor/1/website" target="_blank"><img src="https://opencollective.com/akaunting/sponsor/1/avatar.svg"></a>
<a href="https://opencollective.com/akaunting/sponsor/2/website" target="_blank"><img src="https://opencollective.com/akaunting/sponsor/2/avatar.svg"></a>
<a href="https://opencollective.com/akaunting/sponsor/3/website" target="_blank"><img src="https://opencollective.com/akaunting/sponsor/3/avatar.svg"></a>
<a href="https://opencollective.com/akaunting/sponsor/4/website" target="_blank"><img src="https://opencollective.com/akaunting/sponsor/4/avatar.svg"></a>
<a href="https://opencollective.com/akaunting/sponsor/5/website" target="_blank"><img src="https://opencollective.com/akaunting/sponsor/5/avatar.svg"></a>
<a href="https://opencollective.com/akaunting/sponsor/6/website" target="_blank"><img src="https://opencollective.com/akaunting/sponsor/6/avatar.svg"></a>
<a href="https://opencollective.com/akaunting/sponsor/7/website" target="_blank"><img src="https://opencollective.com/akaunting/sponsor/7/avatar.svg"></a>
<a href="https://opencollective.com/akaunting/sponsor/8/website" target="_blank"><img src="https://opencollective.com/akaunting/sponsor/8/avatar.svg"></a>
<a href="https://opencollective.com/akaunting/sponsor/9/website" target="_blank"><img src="https://opencollective.com/akaunting/sponsor/9/avatar.svg"></a>



## License

Akaunting is released under the [GPLv3 license](LICENSE.txt).
