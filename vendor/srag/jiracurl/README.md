# JiraCurl Library for ILIAS Plugins

Connect to Jira via Curl

## Usage

### Composer
First add the following to your `composer.json` file:
```json
"require": {
  "srag/jiracurl": ">=0.1.0"
},
```

And run a `composer install`.

If you deliver your plugin, the plugin has it's own copy of this library and the user doesn't need to install the library.

Tip: Because of multiple autoloaders of plugins, it could be, that different versions of this library exists and suddenly your plugin use an older or a newer version of an other plugin!

So I recommand to use [srag/librariesnamespacechanger](https://packagist.org/packages/srag/librariesnamespacechanger) in your plugin.

## Requirements
* ILIAS 5.3 or ILIAS 5.4
* PHP >=7.0

## Adjustment suggestions
* External users can report suggestions and bugs at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_LJIRACURL
* Adjustment suggestions by pull requests via github
* Customer of studer + raimann ag: 
	* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/LJIRACURL
	* Bug reports under https://jira.studer-raimann.ch/projects/LJIRACURL
