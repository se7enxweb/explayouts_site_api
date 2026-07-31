# explayouts_site_api

Site API bridge between Exponential Layouts and `expsite_api` on Exponential Legacy / Exponential 6. It lets the layouts stack work with `expSiteApiContent` / `expSiteApiLocation` value objects instead of raw content/location objects: loading them by ID, converting them to content browser items, generating URLs for them and providing them as block parameter values.

Exponential Legacy port inspired by `netgen-layouts/layouts-ibexa-site-api`.

## Key classes

| Class | File | Purpose |
|-------|------|---------|
| `expLayoutsSiteApiItemValueLoader` | `classes/explayoutssiteapiitemvalueloader.php` | Loads content/location value objects by ID or remote ID |
| `expLayoutsSiteApiValueConverter` | `classes/explayoutssiteapivalueconverter.php` | Converts Site API value objects to `expLayoutsContentBrowserItem` |
| `expLayoutsSiteApiItemValueUrlGenerator` | `classes/explayoutssiteapiitemvalueurlgenerator.php` | Generates URL aliases for Site API value objects |
| `expLayoutsSiteApiValueObjectProviderContent` | `classes/explayoutssiteapivalueobjectprovidercontent.php` | Block parameter provider returning `expSiteApiContent` |
| `expLayoutsSiteApiValueObjectProviderLocation` | `classes/explayoutssiteapivalueobjectproviderlocation.php` | Block parameter provider returning `expSiteApiLocation` |

## Quick example

```php
<?php
$loader = new expLayoutsSiteApiItemValueLoader();
$value = $loader->load( 42, 'content' ); // expSiteApiContent or false

$converter = new expLayoutsSiteApiValueConverter();
$item = $converter->convert( $value ); // expLayoutsContentBrowserItem or false
?>
```

## Documentation

- [INSTALL.md](INSTALL.md) — activation steps and dependencies
- [doc/USAGE.md](doc/USAGE.md) — full API, usage scenarios and customization
- [doc/FAQ.md](doc/FAQ.md) — frequently asked questions
- [doc/TODO.md](doc/TODO.md) — known gaps and planned work
- [doc/SUPPORT.md](doc/SUPPORT.md) — how to get help
