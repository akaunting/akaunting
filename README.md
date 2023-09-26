## TABLE OF CONTENTS
      -Akaunting™
      -Features
      -Website
      -Requirements
      -Framework
      -Installation
      -Contributing
      -Translation
      -Changelog
      -Security
      -Credits
      -License

# Akaunting™

[![Release](https://img.shields.io/github/v/release/akaunting/akaunting?label=release)](https://github.com/akaunting/akaunting/releases)
![Downloads](https://img.shields.io/github/downloads/akaunting/akaunting/total?label=downloads)
[![Translations](https://badges.crowdin.net/akaunting/localized.svg)](https://crowdin.com/project/akaunting)
[![Tests](https://img.shields.io/github/actions/workflow/status/akaunting/akaunting/tests.yml?label=tests)](https://github.com/akaunting/akaunting/actions)
[![License](https://img.shields.io/github/license/akaunting/akaunting?label=license)](LICENSE.txt)

Akaunting is a free, open source and online accounting software designed for small businesses and freelancers. It is built with modern technologies such as Laravel, VueJS, Tailwind, RESTful API etc. Thanks to its modular structure, Akaunting provides an awesome App Store for users and developers.

* [Home](https://akaunting.com) - The house of Akaunting
* [Forum](https://akaunting.com/forum) - Ask for support
* [Documentation](https://akaunting.com/hc/docs) - Learn how to use
* [Developer Portal](https://developer.akaunting.com) - Generate passive income
* [App Store](https://akaunting.com/apps) - Extend your Akaunting
* [Translations](https://crowdin.com/project/akaunting) - Help us translate Akaunting

# Features

Bank Accounts
-Create unlimited bank and cash accounts and track their opening and current balances

Multi Company
-Manage the finances of multiple companies from one admin panel
-Assign users to different companies

Powerfil Reporting
-Get detailed financial reports to help you better visualize all the information you need to improve your business

Client Portal
-Share the transations and invoices with your client and accept bulk payments, online

Recur Everything
-Automactically create invoices, revenues, bills, and payments for ongoing job with just a click

Customer Summary
-See the customer profile, address, list of transactions, and paid, open, and overdue totals at a glance

# Website

To run the application, do the following steps: 
1. Navigate to the Accounting Software
    Click on this link to be directed to the website: https://akaunting.com/
2. New users click on "Get Started" and existing users click on "Log in"
    Two options will appear to download the applciation, cloud or on-premise
        Cloud is using the application on our server (Recommended)
        On-premise is using the application on your own server
3. With the cloud option you will create an account by entering in your name, email, and password and with the on-premise the application will be downloaded on your local machine

## Requirements

* PHP 8.1 or higher
* Database (eg: MySQL, PostgreSQL, SQLite)
* Web Server (eg: Apache, Nginx, IIS)
* [Other libraries](https://akaunting.com/hc/docs/on-premise/requirements/)

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

Please review [our security policy](https://github.com/akaunting/akaunting/security/policy) on how to report security vulnerabilities.

## Credits

* [Denis Duliçi](https://github.com/denisdulici)
* [Cüneyt Şentürk](https://github.com/cuneytsenturk)
* [All Contributors](../../contributors)

## License

Akaunting is released under the [GPLv3 license](LICENSE.txt).
