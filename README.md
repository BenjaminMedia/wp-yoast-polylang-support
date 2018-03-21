# WordPress Yoast Polylang Support

Wordpress must-use plugin that enables Yoast settings to be stored per language created in Polylang
```
composer require bonnier/wp-yoast-polylang-support
```
Install through composer or download the zip from GitHub

The plugin works by hooking into the option filters that WordPress provide by saving a localized version of the Yoast plugin settings.

To edit the settings for a specific language, simply select that language in the Polylang language Drop down.
Selecting no language will mean you are editing the global options that will apply to all languages if language version the does not have a setting set. 
