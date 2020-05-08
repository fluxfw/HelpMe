# HelpMe ILIAS Plugin Description

Support button:
![Support button](./images/support_button.png)

Support button with enabled show tickets:
![Support button with enabled show tickets](./images/support_button_dropdown.png)

Support UI:
![Support UI](./images/support_ui.png)

Show tickets UI:
![Show tickets UI](./images/show_tickets_ui.png)

Config:
![Config](./images/config.png)

Config projects table:
![Config projects table](./images/config_projects_table.png)

Config project:
![Config project](./images/config_project.png)

## Notifications config
You have a `support` property (See more in [Class Support](./src/Support/Support.php)) for specific fields in both subject and body.

In body you have also a `fields` (Array of [Class SupportField](src/Support/SupportField.php)) for dynamic fields.

It uses the twig tempate engine (See more at https://twig.symfony.com/doc/1.x/templates.html).

So you can either use a for loop to fill the notification body dynamic like:
```html
{% for field in fields %}
<p>
	<h2>{{ field.label |e }}</h2>
	{{ field.value |e }}
</p>
<br>
{% endfor %}
```

or fill only specific support fields like:
```html
<h1>{{ support.title |e }}</h1>
<p>{{ support.description |e }}</p>
<small>{{ support.page_reference |e }}</small>
```

or both mixed like:
```html
{% for field in fields %}
{% if field.getKey != "page_reference" %}
<p>
	<h2>{{ field.label |e }}</h2>
	{{ field.value |e }}
</p>
<br>
{% endif %}
{% endfor %}
<small>{{ support.page_reference |e }}</small>
```

Note: For safety reasons Jira API does not supports HTML and will escape HTML
