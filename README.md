## Installation

### Install HelpMe-Plugin
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook
git clone -b develop git@git.studer-raimann.ch:ILIAS/Plugins/HelpMe.git HelpMe
```
Update, activate and config the plugin in the ILIAS Plugin Administration

If yout want use the Jira recipient with oAuth authorization you can find a guide to config your Jira installation: https://developer.atlassian.com/cloud/jira/platform/jira-rest-api-oauth-authentication/

### Dependencies
* [composer](https://getcomposer.org)
* [npm](https://nodejs.org)
* [html2canvas](https://www.npmjs.com/package/html2canvas)
* [sinergi/browser-detector](https://packagist.org/packages/sinergi/browser-detector)
* [srag/activerecordconfig](https://packagist.org/packages/srag/activerecordconfig)
* [srag/dic](https://packagist.org/packages/srag/dic)
* [srag/jiracurl](https://packagist.org/packages/srag/jiracurl)
* [srag/removeplugindataconfirm](https://packagist.org/packages/srag/removeplugindataconfirm)

Please use it for further development!

### Adjustment suggestions
* Adjustment suggestions by pull requests on https://git.studer-raimann.ch/ILIAS/Plugins/HelpMe/tree/develop
* Adjustment suggestions which are not yet worked out in detail by Jira tasks under https://jira.studer-raimann.ch/projects/PLHM
* Bug reports under https://jira.studer-raimann.ch/projects/PLHM
* For external users please send an email to support-custom1@studer-raimann.ch

### Plugin SLA
Wir lieben und leben die Philosophie von Open Source Software! Die meisten unserer Entwicklungen, welche wir im Kundenauftrag oder in Eigenleistung entwickeln, stellen wir öffentlich allen Interessierten kostenlos unter https://github.com/studer-raimann zur Verfügung.

Setzen Sie eines unserer Plugins professionell ein? Sichern Sie sich mittels SLA die termingerechte Verfügbarkeit dieses Plugins auch für die kommenden ILIAS Versionen. Informieren Sie sich hierzu unter https://studer-raimann.ch/produkte/ilias-plugins/plugin-sla.

Bitte beachten Sie, dass wir nur Institutionen, welche ein SLA abschliessen Unterstützung und Release-Pflege garantieren.
