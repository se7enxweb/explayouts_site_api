<?php
class expLayoutsSiteApiItemValueLoader
{
    protected $contentService;
    protected $locationService;

    public function __construct()
    {
        $this->contentService = expSiteApi::content();
        $this->locationService = expSiteApi::location();
    }

    public function load( $value, $valueType = null )
    {
        if ( $valueType === 'location' )
            return $this->locationService->load( (int)$value );

        if ( $valueType === 'content' )
            return $this->contentService->load( (int)$value );

        $content = $this->contentService->load( (int)$value );
        if ( $content instanceof expSiteApiContent && $content->isAvailable() )
            return $content;

        $location = $this->locationService->load( (int)$value );
        if ( $location instanceof expSiteApiLocation && $location->isAvailable() )
            return $location;

        return false;
    }

    public function loadByRemoteId( $remoteId )
    {
        return expSiteApi::content()->loadByRemoteId( $remoteId );
    }
}
