# Installing explayouts_site_api

## Requirements

- Exponential Legacy / Exponential 6
- PHP 8.1, 8.2, 8.3 or 8.4

## Dependencies

- `expsite_api` — provides `expSiteApi`, `expSiteApiContent` and `expSiteApiLocation`, which every class in this extension consumes. Activate it first.
- `explayouts_content_browser` — provides `expLayoutsContentBrowserItem`, the conversion target of the value converter.

## Steps

1. Place the extension in `extension/explayouts_site_api`.

2. Activate it (after its dependencies) in `settings/override/site.ini.append.php`:

   ```ini
   [ExtensionSettings]
   ActiveExtensions[]=expsite_api
   ActiveExtensions[]=explayouts_content_browser
   ActiveExtensions[]=explayouts_site_api
   ```

   To activate it for a single siteaccess only, use `ActiveAccessExtensions[]` in `settings/siteaccess/<name>/site.ini.append.php` instead.

3. Regenerate autoloads:

   ```bash
   php bin/php/ezpgenerateautoloads.php -e
   ```

4. Clear caches:

   ```bash
   php bin/php/ezcache.php --clear-all --purge --allow-root-user
   ```
