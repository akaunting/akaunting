# Akaunting™

 ![Latest Stable Version](https://img.shields.io/github/release/akaunting/akaunting.svg) ![Total Downloads](https://img.shields.io/github/downloads/akaunting/akaunting/total.svg) [![Crowdin](https://d322cqt584bo4o.cloudfront.net/akaunting/localized.svg)](https://crowdin.com/project/akaunting) ![Build Status](https://travis-ci.com/akaunting/akaunting.svg)

Akaunting is a free, open source and online accounting software designed for small businesses and freelancers. It is built with modern technologies such as Laravel, VueJS, Bootstrap 4, RESTful API etc. Thanks to its modular structure, Akaunting provides an awesome App Store for users and developers.

* [Home](https://akaunting.com) - The house of Akaunting
* [Blog](https://akaunting.com/blog) - Get the latest news
* [Forum](https://akaunting.com/forum) - Ask for support
* [Documentation](https://akaunting.com/docs) - Learn how to use
* [Translations](https://crowdin.com/project/akaunting) - Akaunting in your language

## Requirements

* PHP 7.2 or higher
* Database (eg: MySQL, PostgreSQL, SQLite)
* Web Server (eg: Apache, Nginx, IIS)
* [Other libraries](https://akaunting.com/docs/requirements)

## Framework

Akaunting uses [Laravel](http://laravel.com), the best existing PHP framework, as the foundation framework and [Module](https://github.com/akaunting/module) package for Apps.

## Installation

* Install [Composer](https://getcomposer.org/download) and [Npm](https://nodejs.org/en/download/)
* Download the [repository](https://github.com/akaunting/akaunting/archive/master.zip) and unzip into your server
* Open and point your command line to the directory you unzipped Akaunting
* Run the following commands: `composer install` , `npm install` , `npm run dev`
* Finally, launch the [web installer](https://akaunting.com/docs/installation) or run the following command:

```bash
php artisan install --db-host="localhost" --db-name="my_db" --db-username="my_admin" --db-password="my_pass" --company-name="My Company" --company-email="my@company.com" --admin-email="my@company.com" --admin-password="123456"
```

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
