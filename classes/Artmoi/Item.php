<?php

class Artmoi_Item
{
    public $item;

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
    public $city;
    public $country;
    public $latitude;
    public $longitude;

    public $width;
    public $height;
    public $depth;
    public $unit;

    public $year;
    public $month;


    public static function buildFromMeta($detail, $image, $thumbnail)
    {
        $item = new Artmoi_Item();

        error_log("build from meta/media");

        foreach ($detail as $key => $value) {
            if (property_exists($item, $key)) {
                error_log("Settings the Key $key");

                if (is_array($value) && count($value) == 1) {
                    $item->$key = $value[0];
                } else {
                    $item->$key = $value;
                }

            } else {
                if ($key == 'artmoiObjectId') {
                    $item->objectId = $value[0];
                }
                if ($key == 'artmoiCollectionId') {
                    $item->collectionId = $value[0];
                }
                if ($key == 'tag') {
                    if (is_array($value)) {
                        $item->tags = $value;
                    }
                }

            }
        }

        $item->image = $image;
        $item->thumbnail = $thumbnail;

        return $item;
    }

    public static function buildFromApi($data)
    {

        $item = new Artmoi_Item();
        if($data){
            foreach ($data as $key => $value) {
                if ($key == 'medium') {
                    $value = $value->name;
                }

                if (property_exists($item, $key)) {
                     $item->$key = $value;

                } else {
                    if ($key == 'creators') {
                        $item->creator = ($value[0]->displayName) ? $value[0]->displayName : "";
                    }
                    if ($key == 'size') {
                        $item->width = ($value->width) ? $value->width : 0;
                        $item->height = ($value->height) ? $value->height : 0;
                        $item->depth = ($value->depth) ? $value->depth : 0;
                        $item->unit = ($value->units) ? $value->units->value : "";
                    }

                    if ($key == 'creationDate') {
                        $item->year = ($value->year) ? $value->year : '';
                        $item->month = ($value->month) ? $value->month : '';
                    }

                    if ($key == 'location') {
                        $item->address = ($value->address) ? $value->address : '';
                        $item->city = ($value->city) ? $value->city : '';
                        $item->country = ($value->country) ? $value->country : '';
                        if ($value->geoPoint) {
                            $item->latitude = ($value->geoPoint->latitude) ? $value->geoPoint->latitude : '';
                            $item->longitude = ($value->geoPoint->longitude) ? $value->geoPoint->longitude : '';
                        }
                    }

                }
            }
        }

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

    public function formattedDate()
    {
        $date = '';

//        if( $this->month )
//        {
//            $date .= $this->month;
//        }
//        if( $this->month and $this->day )
//        {
//            $date .= ' ';
//        }
//        if( $this->day )
//        {
//            $date .= $this->day;
//        }
//        if( ($this->month or $this->day) and $this->year )
//        {
//            $date .= ' ';
//        }
//        if( $this->year )
//        {
//            $date .= $this->year;
//        }

        return $date;
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