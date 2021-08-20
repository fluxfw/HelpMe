# Changelog

## [6.2.2]
- Twig PHP 7.4 patch

## [6.2.1]
- Twig PHP 7.4 patch

## [6.2.0]
- Switched to main branch
- ILIAS 7 support

## [6.1.10]
- Change utils url

## [6.1.9]
- Update urls

## [6.1.8]
- Ignore not supported languages

## [6.1.7]
- Update readme

## [6.1.6]
- Update project url

## [6.1.5]
- Fix attach screenshots to issue

## [6.1.4]
- Fix project and issue selector

## [6.1.3]
- Fix project and issue selector
- Ping after each object for not ILIAS auto set inactive cron job if during longer
- Update readme and keywords

## [6.1.2]
- `Ilias7PreWarn`

## [6.1.1]
- Dev tools

## [6.1.0]
- ILIAS 6 support
- Min. PHP 7.2
- Remove ILIAS 5.3 & ILIAS 5.4 support

## [6.0.6]
- Disable submit button on submit for prevent multiple support requests for one
- Fix escaped html line breaks

## [6.0.5]
- Fix

## [6.0.4]
- Fix selected language not apply in public area

## [6.0.3]
- Fix

## [6.0.2]
- Info text can now be set multilang

## [6.0.1]
- Fixes

## [6.0.0]
- Make fields full configurable
- System infos are now the raw user agent
- May fix reopen again on each page if support link was open

## [5.0.1]
- Fix cron if no tickets

## [5.0.0]
- Create jira service desk request
- Other improvments and fixes

## [4.2.2]
- Display placeholder types in notification form

## [4.2.1]
- Fix issue types config

## [4.2.0]
- Include page reference in support
- Notification `fields` variable changes, is now an array of [Class SupportField](src/Support/SupportField.php) (Tries to automatic migrate)
- Possibility to hide 'Show tickets' config hints, if not used

## [4.1.3]
- Support title info

## [4.1.2]
- Fix select config not apply first role if "Select all" not selected
- Remove know issue, because it fixed in the latest ILIAS core version ("There is a known issue in the ILIAS core object svg icons (The `foreignObject` tag). This tag prevents to take screenshots in Chrome/Safari, because it could reveal something about the current user. [Here](https://mantis.ilias.de/view.php?id=25040) is the ILIAS Mantis report")

## [4.1.1]
- Fix migrate `twigParser` class
- Fix `fix_version` is `null` and no `string`

## [4.1.0]
- Notifications4Plugin library

## [4.0.2]
- Fix goto show tickets not work on ILIAS 5.3

## [4.0.1]
- Disable curl verbose

## [4.0.0]
- Allow projects can have multiple issue types and its fixed version
- Allow to select issue type in support UI
- If you use Jira recipient and if `HelpMeCron` is installed and activated, it enabled the new tickets UI to list unresolved Jira tickets
- Restrict show tickets per project
- Updated screenshots
- Fix `HelpMeException`

## [3.3.3]
- Fixes

## [3.3.2]
- Set priority directly to Jira ticket
- Use RemoveDataConfirm/ScreenshotsInputGUI bundled language

## [3.3.1]
- Config confirmation mail
- Supports for each recipient a differnt template
- Remove old config classes
- Fix possible config crash

## [3.3.0]
- Use Notifications4Plugins template
- Using some new ILIAS 5.3 UI's

## [3.2.0]
- The key in support link can now differ to the project key
- Fix version can now be set for each project
- Known issues in Chrome/Safari

## [3.1.5]
- Default Issue-Type

## [3.1.4]
- Supports ILIAS 5.4

## [3.1.3]
- No anonymous name and email

## [3.1.2]
- Phone is now voluntary

## [3.1.1]
- Sort projects

## [3.1.0]
- Log Jira errors
- Move jira issue type to each project

## [3.0.10]
- Fix

## [3.0.9]
- Fix

## [3.0.8]
- Fix

## [3.0.7]
- PHPVersionChecker
- Fix Jira

## [3.0.6]
- Project table: Allows to select columns

## [3.0.5]
- Project database table

## [3.0.4]
- Allows to change the username in public section, because anonymous user name may not make sense

## [3.0.3]
- Use plugin specif namespace of libraries

## [3.0.2]
- Fix ILIAS sub root folder

## [3.0.1]
- Add some screenshots
- Fix git clone

## [3.0.0]
- Multiple screenshots upload support
- Multiple projects selection support
- Support link inclusive project pre selection
- Correctly works now on ILIAS public section
- Refactoring

## [2.0.0]
- Fix headers in emails are too large
- Fix field "Steps to Reproduce" not required
- Field "Info" supports rte
- Not select first option of Priority and use <please select>
- Reply sender email in support email
- Readonly system infos field and detect it automatic
- Fix no html body in jira because not supported over api
- Jira oAuth now works
- Roles description

## [1.0.1]
- Jira support

## [1.0.0]
- First version
