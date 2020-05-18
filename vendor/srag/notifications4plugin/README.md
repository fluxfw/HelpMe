# Notifications4Plugin Library for ILIAS Plugins

This library offers a quick and easy way to create and send notifications in any language. The notifications are usually configured in the ui of Notifications4Plugin and can then be sent for instance as an email by other plugins dynamic

The text of the notifications is parsed by default with the [Twig template engine!](https://twig.symfony.com/doc/1.x/templates.html), meaning the developer can replace placeholders and use if statements and loops

The development interface offers easy methods to create, modify and send notifications

## Usage

### Composer
First add the following to your `composer.json` file:
```json
"require": {
  "srag/notifications4plugin": ">=0.1.0"
},
```
And run a `composer install`.

If you deliver your plugin, the plugin has it's own copy of this library and the user doesn't need to install the library.

Tip: Because of multiple autoloaders of plugins, it could be, that different versions of this library exists and suddenly your plugin use an older or a newer version of an other plugin!

So I recommand to use [srag/librariesnamespacechanger](https://packagist.org/packages/srag/librariesnamespacechanger) in your plugin.

### PHP 7.0
You can use this library with PHP 7.0 by using the `PHP72Backport` from [srag/librariesnamespacechanger](https://packagist.org/packages/srag/librariesnamespacechanger)

## Using trait
Your class in this you want to use Notifications4Plugin needs to use the trait `Notifications4PluginTrait`
```php
...
use srag\Notifications4Plugin\HelpMe\x\Utils\Notifications4PluginTrait;
...
class x {
...
use Notifications4PluginTrait;
...
```

## Notification ActiveRecord
First you need to init the `Notification` and `NotificationLanguage` active record classes with your own table name prefix. Please add this very early in your plugin code
```php
self::notifications4plugin()->withTableNamePrefix(ilXPlugin::PLUGIN_ID)->withPlugin(self::plugin())->withPlaceholderTypes([
    'user' => 'object ' . ilObjUser::class,
    'course' => 'object ' . ilObjCourse::class,
    'id' => 'int'
]);
```

Add an update step to your `dbupdate.php`
```php
...
<#x>
<?php
\srag\Notifications4Plugin\HelpMe\x\Repository::getInstance()->installTables();
?>
```

and not forget to add an uninstaller step in your plugin class too
```php
self::notifications4plugin()->notifications()->dropTables();
```

## Ctrl classes
```php
...
/**
 * ...
 *
 * @ilCtrl_isCalledBy srag\Notifications4Plugin\HelpMe\x\Notification\NotificationsCtrl: x
 */
class x
{
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
    public function updateLanguages(/*?array*/ $a_lang_keys = null)/*:void*/ {
		parent::updateLanguages($a_lang_keys);

		self::notifications4plugin()->installLanguages();
	}
...
```

## Migrate from old global plugin
Add to your `dbupdate.php` like:
```php
if (\srag\Notifications4Plugin\HelpMe\x\Notification\Repository::getInstance()->migrateFromOldGlobalPlugin(x::TEMPLATE_NAME) === null) {

	$notification = \srag\Notifications4Plugin\HelpMe\x\Notification\Repository::getInstance()->factory()->newInstance();

	$notification->setName(x::TEMPLATE_NAME);

	// TODO: Fill $notification with your default values

	\srag\Notifications4Plugin\HelpMe\x\Notification\Repository::getInstance()->storeNotification($notification);
}
```

## Get notification(s)
Main
```php
// Get the notification by name
$notification = self::notifications4plugin()->notifications()->getNotificationByName(self::MY_UNIQUE_NAME);

```
Other
```php
// Get the notification by id
$notification = self::notifications4plugin()->notifications()->getNotificationById(self::MY_UNIQUE_ID);

// Get the notifications
$notifications = self::notifications4plugin()->notifications()->getNotifications();
```

## Send a notification
```php
// Send the notification as external mail
$sender = self::notifications4plugin()->sender()->factory()->externalMail('from_email', 'to_email');

// Send the notification as internal mail
$sender = self::notifications4plugin()->sender()->factory()->internalMail('from_user', 'to_user');

// vcalendar
$sender = self::notifications4plugin()->sender()->factory()->vcalendar(...);

// Implement a custom sender object
// Your class must implement the interface `srag\Notifications4Plugin\HelpMe\x\Sender\Sender`
```

```php
// Prepare placeholders, note that the keys are the same like declared in the notification template
$placeholders = [
  'user' => new ilObjUser(6),
  'course' => new ilObjCourse(12345)
];
```

```php
// Sent the notification in english first (default langauge) and in german again
self::notifications4plugin()->sender()->send($sender, $notification, $placeholders);
self::notifications4plugin()->sender()->send($sender, $notification, $placeholders, 'de');
```

## Create a notification
```php
$notification = self::notifications4plugin()->notifications()->factory()->newInstance();

$notification->setName(self::MY_UNIQUE_NAME); // Use the name as unique identifier to retrieve this object later
$notification->setDefaultLanguage('en'); // The text of the default language gets substituted if you try to get the notification of a langauge not available
$notification->setTitle('My first notification');
$notification->setDescription("I'm a description");

// Add subject and text for english and german
$notification->setSubject('Hi {{ user.getFullname }}', 'en');
$notification->setText('You joined the course {{ course.getTitle }}', 'en');
$notification->setSubject('Hallo {{ user.getFullname }}', 'de');
$notification->setText('Sie sind nun Mitglied in folgendem Kurs {{ course.getTitle }}', 'de');

self::notifications4plugin()->notifications()->storeNotification($notification);
```

## Duplicate a notification
```php
$duplicated_notification = self::notifications4plugin()->notifications()->duplicateNotification($notification);
```

## Delete a notification
```php
self::notifications4plugin()->notifications()->deleteNotification($notification);
```

## Get parsed subject and text of a notification
You can get the parsed subject and text from a notification, for example to display it on screen.

```php
$placeholders = [
  'course' => new ilObjCourse(1234),
  'user' => new ilObjUser(6)
];

$parser = self::notifications4plugin()->parser()->getParserForNotification($notification);

$subject = self::notifications4plugin()->parser()->parseSubject($parser, $notification, $placeholders);
$text = self::notifications4plugin()->parser()->parseText($parser, $notification, $placeholders);
```

## Implement a custom parser
Your class must extends `srag\Notifications4Plugin\HelpMe\x\Parser\AbstractParser`

You can add it
```php
self::notifications4plugin()->parser()->addParser(new CustomParser());
```

## Requirements
* ILIAS 5.4 or ILIAS 6
* PHP >=7.2

## Adjustment suggestions
* External users can report suggestions and bugs at https://plugins.studer-raimann.ch/goto.php?target=uihk_srsu_PLNOTIFICATION
* Adjustment suggestions by pull requests via github
* Customer of studer + raimann ag: 
	* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/PLNOTIFICATION
	* Bug reports under https://jira.studer-raimann.ch/projects/PLNOTIFICATION
