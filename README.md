# Akaunting™

[![Release](https://img.shields.io/github/v/release/akaunting/akaunting?label=release)](https://github.com/akaunting/akaunting/releases)
![Downloads](https://img.shields.io/github/downloads/akaunting/akaunting/total?label=downloads)
[![Translations](https://badges.crowdin.net/akaunting/localized.svg)](https://crowdin.com/project/akaunting)
[![Tests](https://img.shields.io/github/workflow/status/akaunting/akaunting/Tests?label=tests)](https://github.com/akaunting/akaunting/actions)
[![License](https://img.shields.io/github/license/akaunting/akaunting?label=license)](LICENSE.txt)

Akaunting is a free, open source and online accounting software designed for small businesses and freelancers. It is built with modern technologies such as Laravel, VueJS, Bootstrap 4, RESTful API etc. Thanks to its modular structure, Akaunting provides an awesome App Store for users and developers.

* [Home](https://akaunting.com) - The house of Akaunting
* [Forum](https://akaunting.com/forum) - Ask for support
* [Documentation](https://akaunting.com/docs) - Learn how to use
* [Developer Portal](https://developer.akaunting.com) - Generate passive income
* [App Store](https://akaunting.com/apps) - Extend your Akaunting
* [Translations](https://crowdin.com/project/akaunting) - Help us translate Akaunting

## Requirements

* PHP 7.3 or higher
* Database (eg: MySQL, PostgreSQL, SQLite)
* Web Server (eg: Apache, Nginx, IIS)
* [Other libraries](https://akaunting.com/docs/requirements)

## Framework

Akaunting uses [Laravel](http://laravel.com), the best existing PHP framework, as the foundation framework and [Module](https://github.com/akaunting/module) package for Apps.

## Installation

* Install [Composer](https://getcomposer.org/download) and [Npm](https://nodejs.org/en/download)
* Clone the repository: `git clone https://github.com/akaunting/akaunting.git`
* Install dependencies: `composer install ; npm install ; npm run dev`
* Install Akaunting:

```bash
php artisan install --db-name="akaunting" --db-username="root" --db-password="pass" --admin-email="admin@company.com" --admin-password="123456"
```

* Create sample data (optional): `php artisan sample-data:seed`

## Contributing

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

* [Denis Duliçi](https://github.com/denisdulici)
* [Cüneyt Şentürk](https://github.com/cuneytsenturk)
* [All Contributors](../../contributors)

## Partners

Each of our partners can help you craft a beautiful, well-architected project. Feel free to get in [contact](https://akaunting.com/contact) with us to become a partner.

* [Creative Tim](https://www.creative-tim.com) is our design partner since Akaunting 2.0 version. They create beautiful UI Kits, Templates, and Dashboards built on top of Bootstrap, Vue.js, React, Angular, Node.js, and Laravel.

## Sponsors

Support Akaunting by becoming a sponsor on [Patreon](https://www.patreon.com/akaunting). Your logo will show up here with a link to your website.

## License

Akaunting is released under the [GPLv3 license](LICENSE.txt).
