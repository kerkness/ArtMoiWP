<?php

/**
 * Class Artmoi_Controller
 */
class Artmoi_Controller
{
    /**
     * @var string
     */
    public $artmoiwp_apikey;

    /**
     * @var string
     */
    public $artmoiwp_syncedReports;
    /**
     * @var string
     */
    public $artmoiwp_syncedCollections;

    /**
     * @var string
     */
    public $artmoiwp_allitems;

    public $userReports;
    public $userCollections;

    /**
     * Get the options
     */
    public function before()
    {
        $this->artmoiwp_apikey = get_option('artmoiwp_apikey');

        $syncedReports = get_option('artmoiwp_syncedReports');
        $syncedCollections = get_option('artmoiwp_syncedCollections');
        $syncedAllItems = get_option('artmoiwp_allitems');

        // Check if empty
        if (!$syncedReports) {
            $syncedReports = '';
        }

        if (!$syncedCollections) {
            $syncedCollections = '';
        }
        if(!$syncedAllItems){
            $syncedAllItems = '';
        }

        $this->artmoiwp_allitems = json_decode($syncedAllItems);
        $this->artmoiwp_syncedReports = json_decode($syncedReports);
        $this->artmoiwp_syncedCollections = json_decode($syncedCollections);

    }


    /**
     * Display the settings page
     */
    public function settings()
    {
        // If we are receiving a new apiKey via POST
        if ($_POST['apiKey']) {
            $apiKey = $_POST['apiKey'];
            update_option('artmoiwp_apikey', $apiKey);
            $this->artmoiwp_apikey = $apiKey;
        }

        // Assign apiKey to view
        Flight::view()->set('apiKey', $this->artmoiwp_apikey);

        // Render the template
        Flight::render('admin/settings');
    }


    /**
     * display the report page
     */
    public function lists()
    {
        wp_enqueue_script('gridContainer', plugins_url('ArtMoiWP/scripts/gridContainer.js'));

        // get the report and collection list
        $reports = $this->getUserReports();
        $collections = $this->getUserCollections();

        Flight::view()->set('apiKey', get_option('artmoiwp_apikey'));

        Flight::render('admin/lists', array('reports' => $reports, 'collections' => $collections, 'syncedReports' => $this->artmoiwp_syncedReports, 'syncedCollections' => $this->artmoiwp_syncedCollections, 'syncedAllItems' => $this->artmoiwp_allitems));
    }

    /**
     * display the dashboard page and recent items
     */
//    public function dashboard()
//    {
    /* recent creations  */
    //if( $_POST['hiddenKey'] )
    //{
    //$postData =  json_decode(urldecode(stripslashes($_POST['objectData'][0])));
    //print "<pre>";
    //print_r($postData);
    //print "</pre>";
    //}

    //Flight::view()->set('gridTitle', __('Recent Creations'));
    //Flight::render('admin/artworkGrid', array('artwork' => $results, 'reports' => $reports), 'grid');
    // Flight::render('admin/dashboard');
//    }


    /**
     * get user reports
     * @return mixed
     */
    public function getUserReports()
    {
        $artmoi = Flight::artmoi();

        $artmoi->params('limit', 100);

        $controller = 'user';
        $action = 'reports';
        $response = $artmoi->call($controller, $action);

        return $response->results();
    }

    /**
     * Get user collection List
     * get group lists
     * @return mixed
     */
    public function getUserCollections()
    {
        $artmoi = Flight::artmoi();

        $artmoi->params('limit', 100);

        $controller = 'user';
        $action = 'collections';
        $response = $artmoi->call($controller, $action);

        return $response->results();
    }



    /**
     * display items
     */
    public function viewItems()
    {
        wp_enqueue_script('items', plugins_url('ArtMoiWP/scripts/items.js'));

        $pageType = $_GET["pageType"];
        $listTitle = $_GET['listTitle'];
        $listId = $_GET['listId'];

//        $page = (Flight::request()->query->page) ? Flight::request()->query->page : 0;
//        $limit = (Flight::request()->query->limit) ? Flight::request()->query->limit : 100;
//        $skip = (Flight::request()->query->skip) ? Flight::request()->query->skip : 0;

        // Get the items
        switch ($pageType) {
            case "report":
                $results = $this->getReportItems($listId);
                break;
            case "collection":
                $results = $this->getCollectionItems($listId);
                break;
//            case "all":
//                $results = $this->getAllItems($limit, $skip);
//                break;
        }

//        $nextSet = $skip + $limit;

        // Render the template
        Flight::render('admin/grid/reportGrid', array('results' => $results, 'pageType' => $pageType), 'reportGrid');
        Flight::render('admin/grid/collectionGrid', array('results' => $results, 'pageType' => $pageType), 'collectionGrid');
        Flight::render('admin/items', array('pageType' => $pageType, 'listId' => $listId, 'listTitle' => $listTitle));
    }

    /**
     * Get all Items user has and display in alphabetical order
     * @return mixed
     *
     */
//    public function getAllItems()
//    {
//        $artmoi = Flight::artmoi();
//
//        $controller = 'user';
//        $action = 'items';
//        $queryString = 'orderby=title&orderdir=asc';
//
//        // limit = 1000
//        $response = $artmoi->call($controller, $action, NULL , $queryString);
//        $items = $response->results();
//
//        return $items;
//    }

    /**
     * get report Items
     * @param $reportId
     * @return mixed
     */
    public function getReportItems($reportId)
    {
        $artmoi = Flight::artmoi();

        // limit is 1000
        $artmoi->params('limit', 1000);

        $queryString = 'orderby=title';
        $controller = 'report';
        $action = $reportId;
        $response = $artmoi->call($controller, $action, NULL, $queryString);

        error_log("total items # : ".count($response->results));
        return $response->results();
    }

    /**
     * get collection items
     * @param $publicId
     * @return mixed
     */
    public function getCollectionItems($publicId)
    {
        $artmoi = Flight::artmoi();

        // limit is 1000
        $artmoi->params('limit', 1000);

        $controller = 'collection';
        $action = $publicId;
        $response = $artmoi->call($controller, $action);


        return $response->results();
    }

    /**
     * get recent creations
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getRecentCreations($page, $limit)
    {
        $artmoi = Flight::artmoi();

        $artmoi->params('p', $page);
        $artmoi->params('limit', $limit);

        $controller = 'creation';
        $action = 'user';
        $response = $artmoi->call($controller, $action);

        return $response->results();
    }

    /**
     * sync images to media gallery
     * @param $post
     * @return int|object
     */
    public function syncCreation($post)
    {
        $url = $post['syncImage'];
        $page = $post['pageType'];
        $listId = $post['listId']; // collection or report ID
        $objectId = $post['syncObjectId']; // image object ID
        $posIndex = $post['posIndex']; // position index number

        // Get the image info e
        $title = $post['syncTitle'];
        $medium = $post['syncMedium'];
        $year = $post['syncCreationDateYear'];
        $month = $post['syncCreationDateMonth'];
        $creator = $post['syncCreator'];
        $copyright = $post['syncCopyright'];
        $price = $post['syncPrice'];
        $status = $post['syncStatus'];
        $width = $post['syncWidth'];
        $height = $post['syncHeight'];
        $depth = $post['syncDepth'];
        $unit = $post['syncUnit'];
        $caption = $post['syncCaption'];
        $address = $post['syncAddress'];
        $tag = $post['syncTags'];

        $desc = "Title: " . $title . "\nMedium: " . $medium . "\nCaption:" . $caption;

        // Find if image is already in Media Files.
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_status' => 'inherit',
        );

        $the_query = new WP_Query($args);

        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $postId = $the_query->post->ID;
                $getObjectId = get_post_meta($postId, "artmoiObjectId", true);  // get saved image IDs
                $getReportId = get_post_meta($postId, "artmoiReportId", true);  // get synced report IDs
                $getCollectionId = get_post_meta($postId, "artmoiCollectionId", true); // get synced collection IDs
                $getAllItemsId = get_post_meta($postId, "artmoiAllItemsId", true);

                if ( ($objectId == $getObjectId) && (($listId == $getReportId) || ($listId == $getCollectionId) || ($listId == $getAllItemsId)) ) {
                    error_log($objectId . " is already in media.");
                    wp_die();
                }
            }
        }

        error_log("wp sync creation called .. " . $url);

        // Get the url content
        $tmp = download_url($url);
        if (is_wp_error($tmp)) {
            // download failed, handle error
            error_log("download failed. ".$tmp->get_error_message());
        }

        $post_id = 0;
        $file_array = array();

        // Set variables for storage
        // fix file filename for query strings
        preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url . '.jpg', $matches);
        $file_array['name'] = basename($matches[0]);
        $file_array['tmp_name'] = $tmp;

        // If error storing temporarily, unlink
        if (is_wp_error($tmp)) {
            @unlink($file_array['tmp_name']);
            $file_array['tmp_name'] = '';

            error_log($tmp->get_error_message());
        }

        // do the validation and storage stuff
        $id = media_handle_sideload($file_array, $post_id, $objectId);

        // If error storing permanently, unlink
        if (is_wp_error($id)) {
            error_log($id->get_error_message());

            @unlink($file_array['tmp_name']);
            return $id;
        }

        // Update posts
        $post = array(
            'ID' => $id,
            'post_title' => $title,
            'attachment_alt' => 'alt text',
            'post_caption' => $caption,
            'post_content' => $desc,
        );

        wp_update_post($post);

        // Update the meta data for this item.
        update_post_meta($id, 'artmoi', true);
        update_post_meta($id, 'artmoiObjectId', $objectId);
        update_post_meta($id, 'posIndex', $posIndex);
        update_post_meta($id, 'title', $title);
        update_post_meta($id, 'caption', $caption);
        update_post_meta($id, 'medium', $medium);
        update_post_meta($id, 'address', $address);
        update_post_meta($id, 'year', $year);
        update_post_meta($id, 'month', $month);
        update_post_meta($id, 'creator', $creator);
        update_post_meta($id, 'tag', $tag);
        update_post_meta($id, 'width', $width);
        update_post_meta($id, 'height', $height);
        update_post_meta($id, 'depth', $depth);
        update_post_meta($id, 'unit', $unit);
        update_post_meta($id, 'price', $price);
        update_post_meta($id,'copyright',$copyright);
        update_post_meta($id,'status',$status);


        // Update the artmoiReportId or CollectionId meta. It will use for checking synced items
        if ($page == "report") {
            update_post_meta($id, 'artmoiReportId', $listId);
        } elseif ($page == "collection") {
            update_post_meta($id, 'artmoiCollectionId', $listId);
        }
//           elseif($page == "all"){
//            update_post_meta($id, 'artmoiAllItemsId',$listId);
//        }

        $src = wp_get_attachment_url($id);

        error_log("Wordpress media url is now $src");

        $response = new Artmoi_Response();
        $response->results($url);

        echo json_encode($response);

        $this->saveSyncedList();


        wp_die(); // this is required to terminate immediately and return a proper response

    }

    /**
     * Add a synced report ID, name and synced date to artmoiwp_syncedReports option
     * Add a synced collection ID, name and synced date to artmoiwp_syncedCollections option
     */
    public function saveSyncedList()
    {
        $id = $_POST['listId'];
        $name = $_POST['listName'];
        $pageType = $_POST['pageType'];

        // set user timezone
        $getTimezone = get_option('timezone_string');

        // if 'timezone_string' is empty, default timezone will be UTC
        if (!empty($getTimezone)) {
            date_default_timezone_set($getTimezone);
        }
        $timestamp = date("F j, Y, g:i a");

        if ($id) {
            switch ($pageType) {
                case "report":
                    $optionName = "artmoiwp_syncedReports";
                    $listId ="reportId"; $listName = "reportName";
                    break;
                case "collection":
                    $optionName = "artmoiwp_syncedCollections";
                    $listId ="collectionId"; $listName = "collectiontName";
                    break;
//                case "all":
//                    $optionName = "artmoiwp_allitems";
//                    $listId = "allItemsId"; $listName = "allItemsName";
//                    break;
            }
            $option = json_decode(get_option($optionName));

            if(get_option($optionName)){
                foreach($option as $syncedItem){
                    if($syncedItem->$listId == $id){
                        error_log("this $pageType ".$id. "is already synced");
                        wp_die();
                    }
                }
            }

                //add a report ID, name and timestamp to array
            $option[] = array( $listId => $id, $listName => $name, "timestamp" => $timestamp);
            $this->$optionName = $option;
            update_option("$optionName", json_encode( $this->$optionName));
        }

        wp_die(); // this is required to terminate immediately and return a proper response
    }

    /**
     * add custom meta-boxes to page and post
     */
    public function addCustomMeta()
    {
        $screen = array('page', 'post');

        // Add meta-boxes to page and post
        foreach($screen as $type)
        {
            add_meta_Box('template_type', 'ArtMoi Template', array($this,'templateMetabox'), $type, 'side', 'default');
            add_meta_Box('syncedReport_id', 'ArtMoi Reports', array($this, 'reportMetabox'), $type, 'advanced');
//            add_meta_Box('syncedallitems', 'ArtMoi All Items Group', array($this, 'allItemsMetabox'), $type, 'advanced');
            add_meta_Box('syncedCollection_id', 'ArtMoi Collections', array($this, 'collectionMetabox'), $type, 'advanced');
        }

    }


    public function templateMetabox()
    {
        $directory = dirname(__FILE__).'/../../views/frontend/template';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));

        foreach($scanned_directory as $file){
            $fileName[] = substr($file,0,-4);
        }

        $templateSelected = get_post_meta(get_the_ID(), 'templateType', true);

        Flight::render('admin/metabox/templateMetabox',array('templateType' => $fileName, 'templateSelected' => $templateSelected));
    }

    /**
     *  display custom meta-boxes
     * @param $post_id
     */
    public function reportMetabox()
    {

        // Get synced reports list

        if ($this->artmoiwp_syncedReports) // Check if it's empty
        {
            $reports = $this->getUserReports();
            foreach ($this->artmoiwp_syncedReports as $synced) {
                foreach ($reports as $report) {
                    if ($synced->reportId == $report->objectId) {
                        $reportResult[] = $report;
                    }
                }
            }
        }

        $reportSelected = get_post_meta(get_the_ID(), 'syncedReportKey', true);
        // Render report and collection meta boxes
         Flight::render('admin/metabox/reportMetabox', array('reports' => $reportResult, 'reportChecked' => $reportSelected, 'pageType' => "report" ));
    }


    public function collectionMetabox($templateType)
    {
        // Get synced collection list
        if ($this->artmoiwp_syncedCollections)
        {
            $collections = $this->getUserCollections();
            foreach ($this->artmoiwp_syncedCollections as $synced) {
                foreach ($collections as $collection) {
                    if ($synced->collectionId == $collection->publicId) {
                        $collectionResult [] = $collection;
                    }
                }
            }
        }

        // Check the saved meta values from input
        $collectionSelected = get_post_meta(get_the_ID(), 'syncedCollectionKey', true);

        Flight::render('admin/metabox/collectionMetabox', array('collections' => $collectionResult, 'collectionChecked' => $collectionSelected, 'pageType' => "collection"));
    }

//    public function allItemsMetabox($templateType)
//    {
//        // check allitems group is synced or not
//        if($this->artmoiwp_allitems)
//        {
//            $allItems = json_encode($this->artmoiwp_allitems);
//        }
//
//        $allItemsSelected = get_post_meta(get_the_ID(), 'syncedAllItemsKey', true);
//        error_log(" is allitems group selected? :: $allItemsSelected");
//        Flight::render('admin/metabox/allItemsMetabox', array('allItems' => $allItems, 'allItemsChecked' => $allItemsSelected,'pageType' => "allItems"));
//    }


    /**
     * save or delete meta values from input
     * @param $post_id
     */
    public function saveOrDeleteMetaValue($postId)
    {
        // TODO: case) when user leaves without updating on the edit page..
        // delete selected report, collection or all items group
        delete_post_meta($postId, 'syncedReportKey');
        delete_post_meta($postId, 'syncedCollectionKey');
//        delete_post_meta($postId, 'syncedAllItemsKey');
        delete_post_meta($postId, 'artmoiPageType');
        delete_post_meta($postId, 'templateType');

        // When report is selected
        if (strpos($_POST['listSelected'], 'report') !== false) {
            $key = 'syncedReportKey';
            $value = substr($_POST['listSelected'], 7);
            $deleteValue = substr($_POST['listSelected'], 14);
            $pageType = 'report';
        }

        // When collection is selected
        if (strpos($_POST['listSelected'], 'collection') !== false)
        {
            $key = 'syncedCollectionKey';
            $value = substr($_POST['listSelected'], 11);
            $deleteValue = substr($_POST['listSelected'], 18);
            $pageType = 'collection';
        }

        // When all items group is selected
//        if (strpos($_POST['listSelected'], 'allItems') !== false)
//        {
//            $key = 'syncedAllItemsKey';
//            $value = "allItems";
//            $deleteValue = "allItems";
//            $pageType = 'all';
//        }
//

        // Unlink
        if (strpos($_POST['listSelected'], 'delete') !== false) {
            // Delete Report meta value
            delete_post_meta($postId, $key, $deleteValue);
            delete_post_meta($postId, 'artmoiPageType', $pageType);
        }

        // Link
        else {
            // Add Report meta value
            update_post_meta($postId, $key, $value);
            update_post_meta($postId, 'artmoiPageType', $pageType);
        }

        // Save template
        if($_POST['templateSelected']){
            error_log("template was selected for this post.. $postId : ".$_POST['templateSelected']);
            update_post_meta($postId,'templateType',$_POST['templateSelected']);
        }

    }

    /**
     * search images & details and return it
     * @param $postId
     * @param $search
     */
    public function searchData ($postId, $search)
    {
        // get synced report ID or synced colleciton ID
        $reportSelected = get_post_meta($postId, 'syncedReportKey', true);
        $collectionSelected = get_post_meta($postId, 'syncedCollectionKey', true);
        $allItemsSelected = get_post_meta($postId,'syncedAllItemsKey',true);

        // Check weather report or collection is selected in the edit page
        if ($reportSelected || $collectionSelected || $allItemsSelected){
            // If report is selected..
            if ($reportSelected) {
                $key = "artmoiReportId";
                $value = $reportSelected;
            }

            // If collection is selected..
            else if($collectionSelected) {
                $key = 'artmoiCollectionId';
                $value = $collectionSelected;
            }

            // If allItems group is selected..
//            else if($allItemsSelected){
//                $key = 'artmoiAllItemsId';
//                $value = $allItemsSelected;
//            }

            // make a query to search synced images in media
            $args = array(
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'post_status' => 'inherit',
                'meta_query' => array(
                    array(
                        'key' => $key,
                        'value' => $value,
                        'compare' => 'LIKE',
                    )
                )
            );

            $wp_query = new WP_Query($args);

                // Search images have the selected report, collection or allitems group ID in media gallery
                if ($wp_query->have_posts())
                {
                    while ($wp_query->have_posts())
                    {
                        $wp_query->the_post();
                        $queryPostId = $wp_query->post->ID; // get image media post id

                        // Get position index number for ordering
                        $index = get_post_meta($queryPostId);
                        $posIndex = $index['posIndex'][0];

                        // match with the selected ID and the ID image has
                        if($value){
                            $syncedId = get_post_meta($queryPostId, $key, true);
                            if($syncedId == $value)
                            {
                                $current_post_images[$posIndex] = wp_get_attachment_url($queryPostId);
                                $current_post_thumb_file[$posIndex] = wp_get_attachment_thumb_url($queryPostId);
                                $postData[$posIndex] = get_post_meta($queryPostId);
                            }
                        }

                    }
                }

                switch($search)
                {
                    case "detail":
                        return $postData;
                        break;
                    case "image":
                        return $current_post_images;
                        break;
                    case "thumbnail" :
                        return $current_post_thumb_file;
                        break;

                }
        }
    }

    /**
     * Add tags when user publish a post
     * @param $postId
     * @param $detail
     */
    public function addTag($postId,$detail)
    {
        $total = count($detail);

        for($i = 0; $i < $total; $i++)
        {
            $tags = unserialize($detail[$i]['tag'][0]);
            foreach( $tags as $tag){
                wp_set_post_tags($postId, $tag, true);
            }
        }

    }


    /**
     * Insert images from media galleries to the content
     * @param $the_content
     * @param $postId
     * @return string
     */
//    public function insertItems($postId, $theContent, $detail, $image, $thumbnail)
    public function insertItems($postId, $theContent, $items)
    {

        $templateType = get_post_meta($postId, 'templateType', true);
        $total = count($items);

        if(!($templateType == "..." || !($templateType))){

            Flight::render('frontend/modal', array('items' => $items ,
                'total' => $total
            ), 'modal');

            //Fetch template with the image urls and post data
            $imageTemplate = Flight::view()->fetch("frontend/template/$templateType",
                array('items' => $items,
                    'total' => $total,
                ));


//            Flight::render('frontend/modal', array('details' => $detail ,
//                'images' => $image,
//                'total' => $total,
//                'thumbnailImages' => $thumbnail), 'modal');
//
//            //Fetch template with the image urls and post data
//            $imageTemplate = Flight::view()->fetch("frontend/template/$templateType",
//                array('details' => $detail ,
//                    'images' => $image,
//                    'total' => $total,
//                    'thumbnailImages' => $thumbnail,
//                ));

            $theContent .= $imageTemplate;
        }

        return $theContent;
    }


    public function getItems( $args )
    {
        wp_reset_postdata();

        global $post;
        $postId = $post->ID; // the post ID in edit , not published



        $artmoi = Flight::artmoi();

//        $artmoi->params('p', $page);
//        $artmoi->params('limit', $limit);

        $controller = 'creation';
        $action = 'user';

        foreach ($args as $k => $v)
        {
            error_log("Posting from wordpress with params $k = $v");

            if($k == 'daterange')
            {
                $k = 'dateRange';
            }

            $artmoi->params($k, $v);
        }

        $response = $artmoi->call($controller, $action);

        $templateType = get_post_meta($postId, 'templateType', true);

//        error_log("Wordpress template is $postId : $templateType");

        if( ! $templateType )
        {
            $templateType = 'table';
        }

        $items = $response->itemResults();

        Flight::render('frontend/modal', array(
            'items' => $items,
            ), 'modal');

        $output = Flight::view()->fetch('frontend/template/' . $templateType, array(
            'items' => $items,
        ));


        //error_log( json_encode($response->results()) );

        return $output;

    }

}