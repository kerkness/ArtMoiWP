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
     * Get the options
     */
    public function before()
    {
        $this->artmoiwp_apikey = get_option('artmoiwp_apikey');
        $this->artmoiwp_creationpage = get_option('artmoiwp_creationpage');

        $syncedReports = get_option('artmoiwp_syncedReports');
        $syncedCollections = get_option('artmoiwp_syncedCollections');


        // Check if empty
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
     * get report Items
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
                if($collections){
                    foreach ($collections as $collection) {
                        if ($synced->collectionId == $collection->publicId) {
                            $collectionResult [] = $collection;
                        }
                    }
                }
            }
        }

        // Check the saved meta values from input
        $collectionSelected = get_post_meta(get_the_ID(), 'syncedCollectionKey', true);

        Flight::render('admin/metabox/collectionMetabox', array('collections' => $collectionResult, 'collectionChecked' => $collectionSelected, 'pageType' => "collection"));
    }

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
        // Detect devices
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $isMobile = false;
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
           error_log("it's mobile device!");
            $isMobile = true;
        }

        $templateType = get_post_meta($postId, 'templateType', true);
        $total = count($items);
        $this->artmoiwp_creationpage = get_option('artmoiwp_creationpage');
        if(!($templateType == "..." || !($templateType))){

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
    public function getSingleItem ($itemId)
    {
        $artmoi = Flight::artmoi();

        $controller = 'creation';
        $action = $itemId;

        $response = $artmoi->call($controller, $action);

        return $response->results();

    }

    public function getItems( $args )
    {
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

    public function getFeaturedCollection($args)
    {
        wp_reset_postdata();
        wp_enqueue_script('items', plugins_url('ArtMoiWP/scripts/collection.js'));

        $this->artmoiwp_creationpage = get_option('artmoiwp_creationpage');

        global $post;
        $postId = $post->ID; // the post ID in edit , not published

        $artmoi = Flight::artmoi();

        $controller = 'collection';
        $action = $args['collection'];

        $response = $artmoi->call($controller, $action);

//        print_r($response);

        $templateType = get_post_meta($postId, 'templateType', true);

//        error_log("Wordpress template is $postId : $templateType");

        if( ! $templateType )
        {
            $templateType = 'collection';
        }

        $items = $response->itemResults();

        Flight::render('frontend/modal', array(
            'items' => $items,
            'creationPostId' => $this->artmoiwp_creationpage,
        ), 'modal');

        $output = Flight::view()->fetch('frontend/template/' . $templateType, array(
            'items' => $items,
        ));


        //error_log( json_encode($response->results()) );

        return $output;

    }

}