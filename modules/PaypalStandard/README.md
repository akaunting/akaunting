# PayPal Standard app for Akaunting

[![Tests](https://github.com/akaunting/module-paypal-standard/workflows/Tests/badge.svg?label=tests)](https://github.com/akaunting/module-paypal-standard/actions)

## Tests

The workflow runs both [Akaunting](https://github.com/akaunting/akaunting/tree/master/tests) and module test suites. They're configured to run once per week and triggered manually. Therefore, **before** publishing a new release, run the workflow [manually](https://github.com/akaunting/module-paypal-standard/actions?query=workflow%3ATests) and make sure it passes.

## Translations

[Crowdin](https://crowdin.com/project/akaunting-apps) is the home of translators and it's synced (download & upload) with GitHub. The workflow is configured to run once per week and triggered manually. Therefore, **before** publishing a new release, run the workflow [manually](https://github.com/akaunting/module-paypal-standard/actions?query=workflow%3ATranslations) and merge the automatically created PR, if available. Finally, all language files must be listed in the [config](https://github.com/akaunting/module-paypal-standard/blob/master/crowdin.yml) file.
