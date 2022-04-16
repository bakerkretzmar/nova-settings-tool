# Changelog

All notable changes to `nova-settings-tool` will be documented in this file.

## 2.0.0 – 2022-04-16

- Add support for Nova 4

## 1.1.3 – 2021-08-05

- Default Settings tool page title to `Settings` and make it translatable

## 1.1.2 – 2021-07-07

- Set (and allow configuring) the Settings tool page title ([#42](https://github.com/bakerkretzmar/nova-settings-tool/pull/42))

## 1.1.0 – 2020-11-17

- Add a `SettingsChanged` event that fires every time any settings are updated ([#39](https://github.com/bakerkretzmar/nova-settings-tool/pull/39))

## 1.0.1 – 2019-11-15

- Fix an issue where the "Other" panel heading displays even when empty

## 1.0 – 2019-09-29

This is a major release. Upgrading will only take a few minutes and mostly just involves restructuring the config file, but this release is a major departure from previous versions of the package in a few ways, and therefore it **is not backwards compatible.**

#### Breaking changes

##### Completely restructure configuration file to reduce boilerplate and nesting

The top-level `panels` array containing panels, which in turn contained settings, has been replaced with a single `settings` array. The structure of each setting has remained mostly the same, and the panel that a setting belongs to is now specified directly in that setting. Panels are computed based on the unique values of all settings' `panel` keys.

Within individual settings:

- the `name` key has been renamed to `label`
- `description` has been renamed to `help` and now accepts any valid HTML
- `link` has been removed because `help` accepts HTML
- all keys are now optional except `key`

The top-level `navigation` key in the config file has been renamed to `sidebar-label`.

##### Rename config file from `settings.php` to `nova-settings-tool.php`

The package's config file has been renamed to avoid possible conflicts. Fixes [#8](https://github.com/bakerkretzmar/nova-settings-tool/issues/8).

##### Change package namespace from `SettingsTool` to `NovaSettingsTool`

In your `NovaServiceProvider.php`, and anywhere else you're importing the tool, update your imports:

```diff
- use Bakerkretzmar\SettingsTool\SettingsTool;
+ use Bakerkretzmar\NovaSettingsTool\SettingsTool;
```

##### Remove support for assigning default values to settings

This package doesn't affect the behaviour of your app, it just provide's a nice interface for managing settings in Nova—so setting something like `'default' => 'true'` on a toggle, as was previously possible, _wouldn't actually set a default value for the setting_, it would just change the way the setting was initially _displayed_, which could be misleading. Reverts [a4ad702](https://github.com/bakerkretzmar/nova-settings-tool/commit/a4ad702f29b9229e4d55f5150cb2deba47079932) and closes [#17](https://github.com/bakerkretzmar/nova-settings-tool/issues/17).

#### Other changes and improvements

- Add `number` and `select` setting types
- Add support for settings file on a custom disk
- Add ability to set a placeholder value on `text` and `textarea` settings
- Simplify interface and consolidate save action
- Adjust styles to more closely match Nova’s
- Add bucketloads of tests

## 0.3.3 – 2019-06-17

- Add support for setting Toggle to be on by default
- Tweak styles to more closely match the rest of Nova

## 0.3.1 – 2019-05-09

- Add `code` setting type

## 0.3.0 – 2019-04-09

- Add `textarea` setting type ([#11](https://github.com/bakerkretzmar/nova-settings-tool/pull/11))
- Fix bug translating the "Settings saved!" message

## 0.2.0 – 2019-03-16

- Allow localization of package's built-in strings and all passed settings ([#9](https://github.com/bakerkretzmar/nova-settings-tool/pull/9))

## 0.1.1 – 2019-01-13

- Initial release
