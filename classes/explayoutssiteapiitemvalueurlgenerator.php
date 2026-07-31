<?php
class expLayoutsSiteApiItemValueUrlGenerator
{
    public function generate( $value )
    {
        if ( $value instanceof expSiteApiLocation )
            return $value->urlAlias();

        if ( $value instanceof expSiteApiContent )
            return $value->mainLocationId() > 0 ? '/' . eZURLAliasML::fetchByNodeID( $value->mainLocationId() ) : '';

        return '';
    }
}
