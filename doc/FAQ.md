# FAQ — explayouts_site_api

## What does `load()` return when I do not pass a value type?

It tries content first: if `expSiteApi::content()->load()` returns an available `expSiteApiContent`, that wins. Otherwise it tries `expSiteApi::location()->load()` and returns an available `expSiteApiLocation`. If neither loads and passes `isAvailable()`, it returns `false`. Pass `'content'` or `'location'` explicitly when the ID space is known — content object IDs and node IDs overlap numerically.

## Can I load a location by remote ID?

No. `loadByRemoteId()` only delegates to `expSiteApi::content()->loadByRemoteId()`; there is no location remote ID path yet (see [TODO.md](TODO.md)).

## Why does `convert()` return `false` for my content value?

`expSiteApiContent` is converted through its object's main node. If the underlying object is missing or `main_node_id` is not positive (e.g. content without a location), conversion fails. `expSiteApiLocation` converts through its node directly and only fails when the node is gone.

## Why do the providers return `null` instead of `false`?

They mirror the upstream value object provider contract: `null` means "no usable value object" — for content that also covers available content without a main location. The loader and converter, by contrast, use `false` for failures.

## Does this extension render anything?

No. It only loads, converts and links value objects. Rendering is done by the Exponential Layouts block templates in your design.
