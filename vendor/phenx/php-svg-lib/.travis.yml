language: php

php:
  - 5.6
  - 7.0
  - 7.1
  - 7.2
  - nightly

before_script:
  - composer self-update
  - composer install --prefer-source --no-interaction --dev

script: phpunit

matrix:
  allow_failures:
    - php: 5.6
    - php: nightly
  fast_finish: true
