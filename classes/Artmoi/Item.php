<?php

class Artmoi_Item
{
    public $images;
    public $image;
    public $thumbnail;

    public $creator;

    public $title;
    public $caption;
    public $medium;
    public $category;
    public $status;
    public $price;
    public $editionNumber;
    public $editionSize;
    public $edition;
    public $tags;
    public $objectId;
    public $copyright;
    public $posIndex;
    public $collectionId;
    public $address;

    public $width;
    public $height;
    public $depth;
    public $unit;

    public $year;
    public $month;

    public static function buildFromMeta($detail, $image, $thumbnail)
    {
        $item = new Artmoi_Item();

        error_log( "build from meta/media" );

        foreach( $detail as $key => $value )
        {
            if( property_exists($item, $key) )
            {
                error_log("Settings the Key $key");

                if( is_array($value) && count($value) == 1 )
                {
                    $item->$key = $value[0];
                }
                else
                {
                    $item->$key = $value;
                }

            }
            else
            {
                if( $key == 'artmoiObjectId' )
                {
                    $item->objectId = $value[0];
                }
                if( $key == 'artmoiCollectionId' )
                {
                    $item->collectionId = $value[0];
                }
                if( $key == 'tag' )
                {
                    if( is_array($value) )
                    {
                        $item->tags = $value;
                    }
                }

                //error_log("Artmoi_Item does not have the key $key");
                //error_log(json_encode($value));
            }
        }

        $item->image = $image;
        $item->thumbnail = $thumbnail;

        //error_log(json_encode($detail));
        //error_log(json_encode($image));
        //error_log(json_encode($thumbnail));

        return $item;
    }

    public static function buildFromApi($data)
    {
        $item = new Artmoi_Item();

        foreach($data as $key => $value)
        {
            if( $key == 'medium' )
            {
                $value = $value->name;
            }


            if( property_exists($item, $key) )
            {
                $item->$key = $value;
            }
            else
            {
                //error_log("Artmoi_Item does not have the key $key");
                //error_log(json_encode($value));

                if( $key == 'size' )
                {
                    $item->width = ( $value->width ) ? $value->width : 0;
                    $item->height = ( $value->height ) ? $value->height : 0;
                    $item->depth = ( $value->depth ) ? $value->depth : 0;
                    $item->unit = ( $value->unit ) ? $value->unit : 0;
                }

                if( $key == 'creationDate' )
                {
                    //error_log("creation date: " . json_encode($value->year));

                    $item->year = ( $value->year ) ? $value->year : '';
                    $item->month = ( $value->month ) ? $value->month : '';
                }

            }
        }

        //error_log("Created item " . $item->objectId . $item->title );

        return $item;
    }

    public function formattedSize()
    {
        $size = '';

        if( $this->height )
        {
            $size .= $this->height;
        }
        if( $this->height and $this->width )
        {
            $size .= ' x ';
        }
        if( $this->width )
        {
            $size .= $this->width;
        }
        if( ($this->height or $this->width) and $this->depth )
        {
            $size .= ' x ';
        }
        if( $this->depth )
        {
            $size .= $this->depth;
        }

        return $size;
    }

    public function imageUrl()
    {
        return ( $this->image ) ? $this->image : $this->images[0]->imageFileSized;
    }

    public function imageThumbnailUrl()
    {
        return ( $this->thumbnail ) ? $this->thumbnail : $this->images[0]->imageFileThumbnail;
    }

}