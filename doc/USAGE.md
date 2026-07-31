# Using explayouts_site_api

All classes are thin bridges over the `expsite_api` services (`expSiteApi::content()`, `expSiteApi::location()`).

## expLayoutsSiteApiItemValueLoader

```php
<?php
$loader = new expLayoutsSiteApiItemValueLoader();

// load( $value, $valueType = null )
$content  = $loader->load( 42, 'content' );    // expSiteApiContent
$location = $loader->load( 152, 'location' );  // expSiteApiLocation

// Without a type, content is tried first, then location; each candidate
// must pass isAvailable(). Returns false when nothing loads.
$value = $loader->load( 42 );

// loadByRemoteId( $remoteId ) — content only
$content = $loader->loadByRemoteId( 'a1b2c3...' );
?>
```

The constructor resolves `expSiteApi::content()` and `expSiteApi::location()` once and reuses them.

## expLayoutsSiteApiValueConverter

```php
<?php
$converter = new expLayoutsSiteApiValueConverter();

// convert( $value ) — expSiteApiLocation converts via its node;
// expSiteApiContent converts via its object's main node.
// Returns expLayoutsContentBrowserItem or false.
$item = $converter->convert( $value );

// convertToArray( $value ) — toArray() hash or false
$row = $converter->convertToArray( $value );
?>
```

Content without a main node (and anything that is not a Site API value object) converts to `false`.

## expLayoutsSiteApiItemValueUrlGenerator

```php
<?php
$urlGenerator = new expLayoutsSiteApiItemValueUrlGenerator();

// generate( $value )
$url = $urlGenerator->generate( $location ); // $location->urlAlias()
$url = $urlGenerator->generate( $content );  // '/' . alias of the main location
$url = $urlGenerator->generate( 'foo' );     // '' for unknown values
?>
```

Note: URL generation for content values has a known reliability gap — see [TODO.md](TODO.md). Prefer passing locations.

## Value object providers

```php
<?php
$contentProvider = new expLayoutsSiteApiValueObjectProviderContent();
// Returns expSiteApiContent when it loads, is available and has a main
// location; null otherwise (also for null input).
$content = $contentProvider->getValueObject( 42 );

$locationProvider = new expLayoutsSiteApiValueObjectProviderLocation();
// Returns expSiteApiLocation when it loads and is available; null otherwise.
$location = $locationProvider->getValueObject( 152 );
?>
```

These mirror the upstream block-parameter value object providers: given the raw parameter value (an ID), they hand the rendering layer a full Site API value object or `null`.

## Scenario: resolving a block's picked item for rendering

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

## Scenario: importing references by remote ID

```php
<?php
$loader = new expLayoutsSiteApiItemValueLoader();
$content = $loader->loadByRemoteId( $row['remote_id'] );
if ( $content instanceof expSiteApiContent )
{
    $nodeId = $content->mainLocationId();
}
?>
```

## Scenario: CLI usage

```bash
php bin/php/ezexec.php ai/bin/tmp/test_site_api_bridge.php --allow-root-user
```

All classes require the bootstrap (they hit the content repository through `expsite_api`).

## Customization

### Settings layer (INI)

This extension ships only `settings/design.ini.append.php` (a `DesignExtensions[]` registration; no templates yet) and reads no INI values of its own. Repository behaviour is configured in `expsite_api`, not here. Any settings follow this stack's cascade, lowest to highest priority:

1. `settings/*.ini` — kernel defaults
2. `extension/<ext>/settings/*.ini.append.php` — extension defaults
3. `settings/siteaccess/<siteaccess>/*.ini.append.php` — siteaccess overrides
4. `extension/<ext>/settings/siteaccess/<siteaccess>/*.ini.append.php` — extension siteaccess overrides
5. `settings/override/*.ini.append.php` — global overrides (always win)

### Template layer (design overrides)

No templates are shipped; the classes return value objects and strings. Rendering happens in your layouts block templates, which are overridden through the normal design cascade in your own design extension.

### PHP layer (extension points)

- All five classes are small, stateless (or constructor-initialized) and final-free — subclass any of them to change one behaviour, e.g. override `expLayoutsSiteApiItemValueUrlGenerator::generate()` to route URLs through `eZURI`/siteaccess-aware helpers, or `expLayoutsSiteApiValueConverter::convert()` to return an extended item class.
- `expLayoutsSiteApiItemValueLoader` resolves its services in the constructor from `expSiteApi::content()` / `expSiteApi::location()`; to substitute repositories, subclass and reassign the `protected $contentService` / `$locationService` properties in your constructor.
- The value objects themselves (`expSiteApiContent`, `expSiteApiLocation`) are extension points of `expsite_api`, not of this bridge.
