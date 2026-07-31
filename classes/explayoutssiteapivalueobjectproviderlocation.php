<?php
class expLayoutsSiteApiValueObjectProviderLocation
{
    public function getValueObject( $value )
    {
        if ( $value === null )
            return null;

        $location = expSiteApi::location()->load( (int)$value );
        return $location instanceof expSiteApiLocation && $location->isAvailable() ? $location : null;
    }
}
