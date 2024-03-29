# srag/requireddata Library for ILIAS Plugins

Config and fill required data

This project is licensed under the GPL-3.0-only license

## Usage

### Composer

First add the following to your `composer.json` file:

```json
"require": {
  "srag/requireddata": ">=0.1.0"
},
```

And run a `composer install`.

If you deliver your plugin, the plugin has it's own copy of this library and the user doesn't need to install the library.

Tip: Because of multiple autoloaders of plugins, it could be, that different versions of this library exists and suddenly your plugin use an older or a newer version of an other plugin!

So I recommand to use [srag/librariesnamespacechanger](https://packagist.org/packages/srag/librariesnamespacechanger) in your plugin.

## Using trait

Your class in this you want to use RequiredData needs to use the trait `RequiredDataTrait`

```php
...
use srag\RequiredData\HelpMe\x\Utils\RequiredDataTrait;
...
class x {
...
use RequiredDataTrait;
...
```

## RequiredData ActiveRecord

First you need to init the `RequiredData` active record classes with your own table name prefix. Please add this very early in your plugin code

```php
self::requiredData()->withTableNamePrefix(ilXPlugin::PLUGIN_ID)->withPlugin(self::plugin());
```

Add an update step to your `dbupdate.php`

```php
...
<#x>
<?php
\srag\RequiredData\HelpMe\x\Repository::getInstance()->installTables();
?>
```

and not forget to add an uninstaller step in your plugin class too

```php
self::requiredData()->dropTables();
```

## Ctrl classes

```php
...
/**
 * ...
 *
 * @ilCtrl_isCalledBy srag\RequiredData\HelpMe\x\Field\FieldsCtrl: x
 */
class x
{
    ...
}
```

```php
...
/**
 * ...
 *
 * @ilCtrl_isCalledBy srag\Plugins\x\Field\FillCtrl: x
 */
class FillCtrl extends AbstractFillCtrl
{
    ...
    const PLUGIN_CLASS_NAME = ilXPlugin::class;
    ...
    /**
     * @inheritDoc
     */
    protected function back() : void
    {
        ...
    }


    /**
     * @inheritDoc
     */
    protected function cancel() : void
    {
        ...
    }

    ...
}
```

## Languages

Expand you plugin class for installing languages of the library to your plugin

```php
...
	/**
     * @inheritDoc
     */
    public function updateLanguages(/*?array*/ $a_lang_keys = null):void {
		parent::updateLanguages($a_lang_keys);

		self::requiredData()->installLanguages();
	}
...
```

## Own fields

Extend `AbstractField`, `AbstractFieldFormBuilder` and `AbstractFillField` (In same folder).

You need to implement a new language variable `required_data_type_x` in your plugin language file.

But you don't need to add an own update or uninstaller step.

Add your `AbstractField` very early in your plugin code (After you call `withTableNamePrefix`)

```php
self::requiredData()->fields()->factory()->addClass(XField::class);
```

### Deliver multi search select with own static options

Just extends the `StaticMultiSearchSelect` classes

### Deliver value which user can't change

Just extends the `DynamicValue` classes

## Requirements

* ILIAS 6.0 - 7.999
* PHP >=7.2
