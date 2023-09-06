---
title: Usage
order: 2
---

# Usage

Start the dump server by calling the artisan command:

```bash
php artisan dump-server
```

You can set the output format to HTML using the `--format` option:

```bash
php artisan dump-server --format=html > dump.html
```

Now you can put regular `dump` statements in your code. Instead of dumping the output directly in the HTTP response, the dumped data will be shown inside of your terminal / the running artisan command.

This is very useful, when you want to dump data from API requests, without having to deal with HTTP errors.