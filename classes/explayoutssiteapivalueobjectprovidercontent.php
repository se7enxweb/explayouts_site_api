<?php
class expLayoutsSiteApiValueObjectProviderContent
{
    public function getValueObject( $value )
    {
        if ( $value === null )
            return null;

        $content = expSiteApi::content()->load( (int)$value );
        return $content instanceof expSiteApiContent && $content->isAvailable() && $content->mainLocationId() > 0
            ? $content
            : null;
    }
}
