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
     * @var number
     */
    public $artmoiwp_creationpage;
    /**
     * @var string
     */

    public $userReports;
    public $userCollections;

    /**
     * Get ArtMoi options
     */
    public function before()
    {
        $this->artmoiwp_apikey = get_option('artmoiwp_apikey');
        $this->artmoiwp_creationpage = get_option('artmoiwp_creationpage');

        $syncedReports = get_option('artmoiwp_syncedReports');
        $syncedCollections = get_option('artmoiwp_syncedCollections');


        // Check if exists
        if (!$syncedReports) {
            $syncedReports = '';
        }

        if (!$syncedCollections) {
            $syncedCollections = '';
        }

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
     * Display report and collection lists
     */
    public function lists()
    {
        wp_enqueue_script('gridContainer', plugins_url('ArtMoiWP/scripts/gridContainer.js'));

        // get the report and collection list
        $reports = $this->getUserReports();
        $collections = $this->getUserCollections();

        Flight::view()->set('apiKey', get_option('artmoiwp_apikey'));

        Flight::render('admin/lists', array('reports' => $reports, 'collections' => $collections, 'syncedReports' => $this->artmoiwp_syncedReports, 'syncedCollections' => $this->artmoiwp_syncedCollections));
    }

    /**
     * Get user reports
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
     * Display report or collection items
     */
    public function viewItems()
    {
        wp_enqueue_script('items', plugins_url('ArtMoiWP/scripts/items.js'));

        $pageType = $_GET["pageType"];
        $listTitle = $_GET['listTitle'];
        $listId = $_GET['listId'];

        // Get the items
        switch ($pageType) {
            case "report":
                $results = $this->getReportItems($listId);
                break;
            case "collection":
                $results = $this->getCollectionItems($listId);
                break;
        }

        // Render the template
        Flight::render('admin/grid/reportGrid', array('results' => $results, 'pageType' => $pageType), 'reportGrid');
        Flight::render('admin/grid/collectionGrid', array('results' => $results, 'pageType' => $pageType), 'collectionGrid');
        Flight::render('admin/items', array('pageType' => $pageType, 'listId' => $listId, 'listTitle' => $listTitle));
    }

    /**
     * Get report Items
     * @param $reportId
     * @return mixed
     */
    public function getReportItems($reportId)
    {
        $artmoi = Flight::artmoi();

        // limit is 1000
        $artmoi->params('limit', 1000);
        $artmoi->params('orderby',"title");

        $controller = 'report';
        $action = $reportId;
        $response = $artmoi->call($controller, $action);

        return $response->results();
    }

    /**
     * Get collection items
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

                if ( ($objectId == $getObjectId) && (($listId == $getReportId) || ($listId == $getCollectionId)) ) {
                    return 0;
                    wp_die();  // this is required to terminate immediately and return a proper response
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
            }
            $option = json_decode(get_option($optionName));

            // check if report or collection is already synced or not
            if(get_option($optionName)){
                foreach($option as $syncedItem){
                    if($syncedItem->$listId == $id){
                        error_log("this $pageType ".$id. "is already synced");
                        wp_die();
                    }
                }
            }

            //Add a report ID, name and timestamp to array
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
            add_meta_Box('syncedCollection_id', 'ArtMoi Collections', array($this, 'collectionMetabox'), $type, 'advanced');
        }

    }

    /**
     * Display Template metabox
     */
    public function templateMetabox()
    {
        // Get the file list in template directory
        $directory = dirname(__FILE__).'/../../views/frontend/template';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));

        foreach($scanned_directory as $file)
        {
            if(!(preg_match("/^\./",$file))){ // do not display any file starts with dot (EX: .DS_Store or .htaccess ...)
            $fileName[] = substr($file,0,-4);
            }
        }

        $templateSelected = get_post_meta(get_the_ID(), 'templateType', true);
        Flight::render('admin/metabox/templateMetabox',array('templateType' => $fileName, 'templateSelected' => $templateSelected));
    }

    /**
     * Display Report metabox
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

        // Display the selected meta value
        $reportSelected = get_post_meta(get_the_ID(), 'syncedReportKey', true);

        Flight::render('admin/metabox/reportMetabox', array('reports' => $reportResult, 'reportChecked' => $reportSelected, 'pageType' => "report" ));
    }

    /**
     * Display Collection metabox
     */
    public function collectionMetabox()
    {
        // Get synced collection list
        if ($this->artmoiwp_syncedCollections)
        {
            $collections = $this->getUserCollections();
            foreach ($this->artmoiwp_syncedCollections as $synced) {
                if($collections){
                    foreach ($collections as $collection) {
                        if ($synced->collectionId == $collection->publicId) {
                            $collectionResult [] = $collection;
                        }
                    }
                }
            }
        }

        // Display the selected meta value
        $collectionSelected = get_post_meta(get_the_ID(), 'syncedCollectionKey', true);

        Flight::render('admin/metabox/collectionMetabox', array('collections' => $collectionResult, 'collectionChecked' => $collectionSelected, 'pageType' => "collection"));
    }

    /**
     * save or delete meta values from input
     * @param $postId
     */
    public function saveOrDeleteMetaValue($postId)
    {
        // delete selected report or collection
        delete_post_meta($postId, 'syncedReportKey');
        delete_post_meta($postId, 'syncedCollectionKey');
        delete_post_meta($postId, 'artmoiPageType');

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

        // Check weather report or collection is selected in the edit page
        if ($reportSelected || $collectionSelected){
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

                // Search images have the selected report or collection in media gallery
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
     * @param $theContent
     * @param $postId
     * @param $items
     * @return mixed
     */
    public function insertItems($postId, $theContent, $items)
    {
        // Detect devices
        $isMobile = false;
        if( wp_is_mobile() ){
            $isMobile = true;
        }
        // get selected template
        $templateType = get_post_meta($postId, 'templateType', true);
        // get total # of items
        $total = count($items);
        // get single creation page ID
        $this->artmoiwp_creationpage = get_option('artmoiwp_creationpage');


        if(!($templateType == "..." || !$templateType )){

            Flight::render('frontend/modal', array('items' => $items ,
                'total' => $total
            ), 'modal');


            //Fetch template with the image urls and post data
            $imageTemplate = Flight::view()->fetch("frontend/template/$templateType",
                array('items' => $items,
                    'total' => $total,
                    'creationPostId'=> $this->artmoiwp_creationpage,
                    'isMobile' => $isMobile,
                ));

            $theContent .= $imageTemplate;
        }

        return $theContent;
    }

    /**
     * Create a shortcode for displaying one item
     * @param $args
     * @return mixed
     */
    public function getSingleItem ($args)
    {
        wp_reset_postdata();

        global $post;
        $postId = $post->ID;

        // fixed template type...
        update_post_meta($postId,'templateType',"single");

        $artmoi = Flight::artmoi();
        $controller = 'creation';
        $output = "";

        foreach( $args as $itemId) {
            $action = $itemId;
            $response = $artmoi->call($controller, $action);
            $item = $response->sigleItemResults();
            $output .= Flight::view()->render("frontend/template/single",array('item' => $item));
        }


        return $output;
    }

    /**
     * Create a shortcode to load items from ArtMoi
     * @param $args
     * @return mixed
     */
    public function getItems( $args )
    {
        // Detect devices
        $isMobile = false;

        if( wp_is_mobile() ){
            $isMobile = true;
        }

        wp_reset_postdata();

        global $post;
        $postId = $post->ID; // the post ID in edit , not published

        $artmoi = Flight::artmoi();

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

        // set default template type
        if( ! $templateType )
        {
            $templateType = 'table';
            update_post_meta($postId,'templateType',"table");
        }

        // create item object
        $items = $response->itemResults();

        Flight::render('frontend/modal', array(
            'items' => $items,
            ), 'modal');

        $output = Flight::view()->fetch('frontend/template/' . $templateType, array(
            'items' => $items,
            'isMobile' => $isMobile,
        ));

        return $output;

    }

    /**
     * Create a short code to load a real time list of items from ArtMoi
     * @param $args
     * @return mixed
     */
    public function getFeaturedCollection($args)
    {
        // Detect devices
        $isMobile = false;

        if( wp_is_mobile() ){
            $isMobile = true;
        }
        wp_reset_postdata();
        wp_enqueue_script('items', plugins_url('ArtMoiWP/scripts/collection.js'));

        $this->artmoiwp_creationpage = get_option('artmoiwp_creationpage');

        global $post;
        $postId = $post->ID; // the post ID in edit , not published

        $artmoi = Flight::artmoi();

        $controller = 'collection';
        $action = $args['collection'];

        $response = $artmoi->call($controller, $action);

        $templateType = get_post_meta($postId, 'templateType', true);

        if( ! $templateType )
        {
            $templateType = 'collection';
            update_post_meta($postId,'templateType',"collection");
        }

        $items = $response->itemResults();

        Flight::render('frontend/modal', array(
            'items' => $items,
            'creationPostId' => $this->artmoiwp_creationpage,
            'isMobile' => $isMobile,
        ), 'modal');

        $output = Flight::view()->fetch('frontend/template/' . $templateType, array(
            'items' => $items,
        ));

        return $output;

    }

}