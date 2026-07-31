<?php
class expLayoutsSiteApiValueConverter
{
    public function convert( $value )
    {
        if ( $value instanceof expSiteApiLocation )
        {
            $node = $value->getNode();
            if ( $node instanceof eZContentObjectTreeNode )
                return new expLayoutsContentBrowserItem( $node );
        }

        if ( $value instanceof expSiteApiContent )
        {
            $object = $value->getObject();
            if ( !$object instanceof eZContentObject )
                return false;

            $nodeId = (int)$object->attribute( 'main_node_id' );
            if ( $nodeId <= 0 )
                return false;

            $node = eZContentObjectTreeNode::fetch( $nodeId );
            if ( $node instanceof eZContentObjectTreeNode )
                return new expLayoutsContentBrowserItem( $node );
        }

        return false;
    }

    public function convertToArray( $value )
    {
        $item = $this->convert( $value );
        if ( !$item instanceof expLayoutsContentBrowserItem )
            return false;

        return $item->toArray();
    }
}
