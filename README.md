#ArtMoiWP

Plugin for integrating ArtMoi content and syncing media files with your ArtMoi account.

##Description
This plugin provides integration features so that you can publish content from your ArtMoi account into your wordpress site.

Use this plugin to sync ArtMoi Collections and Reports with your Wordpress Media Files or use the available shortcodes for pulling images and content directly from the ArtMoi API.

##Installation

*An ArtMoi API Key is required to use this plugin*

###Installing from Zip

1. Download the latest version of this plugin from [http://artmoi.com/downloads](http://artmoi.com/downloads)
2. Extract the contents of the Zip to your *wp-content/plugins* directory
3. Activate the plugin from the Wordpress Dashboard

###Installing from Github

To install from Github clone this repository into your *wp-content/plugins* directory and then update the submodules that are part of the project.

```
cd wp-content/plugins
git clone git@github.com:kerkness/ArtMoiWP.git ArtMoiWP
cd ArtMoiWP
git submodule init
git submodule update
```

###Setting up the plugin

1. Sign into your ArtMoi account at [https://artmoi.me/login](https://artmoi.me/login)
2. Go to Profile > Integrations
3. Generate a new Wordpress API Key or copy the current one
4. Sign into your Wordpress Dashboard
5. Enter your API Key in  ArtMoi > Settings

##Examples and Usage

###Embed a single item

Use the following shortcode to display a single ArtMoi item.

```
[am_item item_id="<Enter ArtMoi Item ID>"]
```

If the item_id is not included the plugin will look for the item_id in the query string.


###Alphabetical Listing

Use the following shortcodes to display a page with an alphabetical listing of items.

```
[am_menu_alpha range_start="a" range_end="z"]
[am_items limit="300" orderby="title"]
```
Choose or customize an ArtMoi Template to use for these results. We recommend *alphatitle* for alphabetical listings.

###Chronological Listing

Use the following shortcodes to display a page with a chronological listing of items.

```
[am_menu_date range_start="1915" range_end="1952" groupby="4"]
[am_items limit="60" orderby="sortDate" daterange="1915-1919"]
```
Choose or customize an ArtMoi Template to use for these results. We recommend *table* for chronological listings.

###Featured Collection

ArtMoi has many featured Public Art collections which can be embedded into any site.

```
[am_featuredCollection collection="<Enter ArtMoi Collection Id>"]
```
Choose or customize an ArtMoi Template to use for these results. We recommend *collection* for featured collections.


###Syncing Reports and Personal Collections

You may also sync ArtMoi Collections and Reports with your wordpress Media Files.

1. From the wordpress dashboard select *ArtMoi > Lists* to see a list of your ArtMoi collections
2. Click *View Collection* or *View Report* to view the items which can be synced
3. Click *Sync These Items* to sync these items with your Wordpress Media Files

You can now use your ArtMoi items just like you would use any other Media files. *Note:* if you edit one of these items using the ArtMoi app you will need to resync them with your blog to see the updates.

4. From the *Edit Post* or *Edit Page* sections of your wordpress dashboard, select any synced collection or report to include in your page/post.
5. Select or edit an ArtMoi template to use for that post/page.

##Links
At [ArtMoi Downloads](http://artmoi.com/downloads) you can find more Apps and Open Source tools for working with ArtMoi content.
