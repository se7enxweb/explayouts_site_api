# TODO — explayouts_site_api

- `expLayoutsSiteApiItemValueUrlGenerator::generate()` concatenates the return value of `eZURLAliasML::fetchByNodeID()` directly into a string for content values; that call does not return a plain alias string, so content URL generation needs to be fixed (fetch the alias text, or reuse `expSiteApiLocation::urlAlias()` via the main location). Location values are unaffected.
- `expLayoutsSiteApiItemValueLoader::loadByRemoteId()` supports content remote IDs only; add a location remote ID path.
- The auto-detect branch of `load()` (no `$valueType`) cannot distinguish overlapping content object IDs and node IDs; document-driven callers should always pass the type — consider making the parameter required.
- `settings/design.ini.append.php` registers `explayouts_site_api` as a design extension, but the extension has no `design/` directory.
- No test coverage; a bootstrap CLI test exercising loader, converter, URL generator and both providers against known fixture content is still to be written.
