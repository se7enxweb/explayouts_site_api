Exponential Layouts Site API
============================

General description
-------------------

Exponential Layouts Site API (`explayouts_site_api`) is the Site API bridge
between Exponential Layouts and `expsite_api` on Exponential Legacy /
Exponential 6. It lets the layouts stack work with `expSiteApiContent` /
`expSiteApiLocation` value objects instead of raw content/location objects:
loading them by ID, converting them to content browser items, generating
URLs for them and providing them as block parameter values.

This extension is an Exponential Legacy port inspired by
`netgen-layouts/layouts-ibexa-site-api` and provides the following
capabilities:

- Value loading - Use this feature to load Site API content and location
  value objects by ID (with optional type auto-detection) or by content
  remote ID.
- Value conversion - Use this feature to convert Site API value objects
  into normalized `expLayoutsContentBrowserItem` objects (or `toArray()`
  hashes) for listings, pickers and JSON output.
- URL generation - Use this feature to produce URL aliases for Site API
  content and location values.
- Block parameter value objects - Use this feature to turn raw block
  parameter values (IDs) into full Site API value objects for the rendering
  layer, mirroring the upstream value object provider contract.

Features
--------

The following features are provided by the Exponential Layouts Site API
extension:

- A value loader, `expLayoutsSiteApiItemValueLoader`:
  - `load( $value, $valueType = null )` — loads `expSiteApiContent`
    (`'content'`) or `expSiteApiLocation` (`'location'`) by ID. Without a
    type, content is tried first, then location; each candidate must pass
    `isAvailable()`. Returns `false` when nothing loads.
  - `loadByRemoteId( $remoteId )` — loads content by remote ID.
  - The constructor resolves `expSiteApi::content()` and
    `expSiteApi::location()` once and reuses them.
- A value converter, `expLayoutsSiteApiValueConverter`:
  - `convert( $value )` — converts an `expSiteApiLocation` via its node, and
    an `expSiteApiContent` via its object's main node, to an
    `expLayoutsContentBrowserItem`; returns `false` for content without a
    main node and for anything that is not a Site API value object.
  - `convertToArray( $value )` — the same conversion as a `toArray()` hash,
    ready for templates and JSON.
- A URL generator, `expLayoutsSiteApiItemValueUrlGenerator`:
  - `generate( $value )` — returns `$location->urlAlias()` for locations,
    the alias of the main location (prefixed with `/`) for content, and an
    empty string for unknown values. (Content URL generation has a known
    reliability gap — see [doc/TODO.md](doc/TODO.md); prefer passing
    locations.)
- Block parameter value object providers, mirroring the upstream contract
  (`null` means "no usable value object"):
  - `expLayoutsSiteApiValueObjectProviderContent::getValueObject( $id )` —
    returns `expSiteApiContent` when it loads, is available and has a main
    location; `null` otherwise (also for `null` input).
  - `expLayoutsSiteApiValueObjectProviderLocation::getValueObject( $id )` —
    returns `expSiteApiLocation` when it loads and is available; `null`
    otherwise.
- All five classes are thin bridges over the `expsite_api` services
  (`expSiteApi::content()`, `expSiteApi::location()`), small, stateless (or
  constructor-initialized) and free of `final` — every one of them is a
  supported subclassing point.
- No rendering of its own: the extension only loads, converts and links
  value objects; rendering is done by the Exponential Layouts block
  templates in your design.

Version
-------

- The current version of Exponential Layouts Site API is 1.0.0
- Last Major update: July 30, 2026

Copyright
---------

- Exponential Layouts Site API is copyright 1998 - 2026 7x
- See: [LICENSE.md](LICENSE.md) for more information on the terms of the
  copyright and license

License
-------

Exponential Layouts Site API is licensed under the GNU General Public
License.

The complete license agreement is included in the [LICENSE.md](LICENSE.md)
file.

Exponential Layouts Site API is free software: you can redistribute it
and/or modify it under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 2 of the License
or at your option a later version.

Exponential Layouts Site API is distributed in the hope that it will be
useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
Public License for more details.

The GNU GPL gives you the right to use, modify and redistribute
Exponential Layouts Site API under certain conditions. The GNU GPL license
is distributed with the software, see the file [LICENSE.md](LICENSE.md).

It is also available at http://www.gnu.org/licenses/gpl.txt

You should have received a copy of the GNU General Public License along with
Exponential Layouts Site API in [LICENSE.md](LICENSE.md). If not, see
http://www.gnu.org/licenses/.

Using Exponential Layouts Site API under the terms of the GNU GPL is free
(as in freedom).

For more information or questions please contact info@se7enx.com

Requirements
------------

The following requirements exist for using the Exponential Layouts Site API
extension:

Exponential version
- Make sure you use Exponential 6 / Exponential Legacy.

PHP version
- Make sure you have PHP 8.1, 8.2, 8.3 or 8.4.

Extension dependencies
- `expsite_api` — provides `expSiteApi`, `expSiteApiContent` and
  `expSiteApiLocation`, which every class in this extension consumes.
  Activate it first.
- `explayouts_content_browser` — provides `expLayoutsContentBrowserItem`,
  the conversion target of the value converter.

Installation
------------

Installation is the standard extension procedure: place the extension in
`extension/explayouts_site_api`, activate it after its dependencies via
`ActiveExtensions[]` (or `ActiveAccessExtensions[]` for a single
siteaccess), regenerate autoloads with
`php bin/php/ezpgenerateautoloads.php -e` and clear caches with
`php bin/php/ezcache.php --clear-all --purge --allow-root-user`.

See [INSTALL.md](INSTALL.md) for the complete step-by-step installation
instructions.

Usage
-----

The extension ships five classes:

| Class | File | Purpose |
|-------|------|---------|
| `expLayoutsSiteApiItemValueLoader` | `classes/explayoutssiteapiitemvalueloader.php` | Loads content/location value objects by ID or remote ID |
| `expLayoutsSiteApiValueConverter` | `classes/explayoutssiteapivalueconverter.php` | Converts Site API value objects to `expLayoutsContentBrowserItem` |
| `expLayoutsSiteApiItemValueUrlGenerator` | `classes/explayoutssiteapiitemvalueurlgenerator.php` | Generates URL aliases for Site API value objects |
| `expLayoutsSiteApiValueObjectProviderContent` | `classes/explayoutssiteapivalueobjectprovidercontent.php` | Block parameter provider returning `expSiteApiContent` |
| `expLayoutsSiteApiValueObjectProviderLocation` | `classes/explayoutssiteapivalueobjectproviderlocation.php` | Block parameter provider returning `expSiteApiLocation` |

A quick example:

```php
<?php
$loader = new expLayoutsSiteApiItemValueLoader();
$value = $loader->load( 42, 'content' ); // expSiteApiContent or false

$converter = new expLayoutsSiteApiValueConverter();
$item = $converter->convert( $value ); // expLayoutsContentBrowserItem or false
?>
```

Resolving a block's picked item for rendering:

```php
<?php
$provider = new expLayoutsSiteApiValueObjectProviderLocation();
$location = $provider->getValueObject( $blockParams['location_id'] );

if ( $location !== null )
{
    $converter = new expLayoutsSiteApiValueConverter();
    $tpl->setVariable( 'item', $converter->convertToArray( $location ) );
    $tpl->setVariable( 'item_url', ( new expLayoutsSiteApiItemValueUrlGenerator() )->generate( $location ) );
}
?>
```

A practical tip: content object IDs and node IDs overlap numerically, so
pass `'content'` or `'location'` explicitly to `load()` whenever the ID
space is known instead of relying on auto-detection.

The full usage guide in [doc/USAGE.md](doc/USAGE.md) covers the complete
API of all five classes, usage scenarios (block rendering, importing
references by remote ID, CLI usage under the bootstrap) and the three
customization layers of this stack: the settings layer (the INI
configuration cascade — repository behaviour is configured in
`expsite_api`, not here), the template layer (rendering happens in your
layouts block templates, overridden through the design cascade) and the PHP
layer (subclassing any of the five classes, e.g. overriding
`expLayoutsSiteApiItemValueUrlGenerator::generate()` or reassigning the
`protected` service properties of the loader).

Documentation
-------------

| Document | Description |
|----------|-------------|
| [INSTALL.md](INSTALL.md) | Requirements, dependencies and step-by-step activation instructions |
| [doc/USAGE.md](doc/USAGE.md) | Full API reference, usage scenarios and the settings/template/PHP customization layers |
| [doc/FAQ.md](doc/FAQ.md) | Frequently asked questions and answers |
| [doc/TODO.md](doc/TODO.md) | Known gaps and planned improvements |
| [doc/SUPPORT.md](doc/SUPPORT.md) | Where and how to get help |
| [LICENSE.md](LICENSE.md) | The complete GNU General Public License agreement |

Troubleshooting
---------------

Read the FAQ
- Some problems are more common than others. The most common ones are listed
  in [doc/FAQ.md](doc/FAQ.md).

Use our support systems
- If you find a bug or defect, please report it to the
  [Exponential Layouts Site API: Issue Tracker](https://github.com/se7enxweb/explayouts_site_api/issues)
- For commercial support and custom development please visit
  [se7enx.com](https://se7enx.com)
