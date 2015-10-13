+function ($) {

    pageNumber = 1;

    $(document.body).on('click', '#syncReport', function( event ){
        // prevent default click behaviour
        event.preventDefault();

        // Change the button label and disable button
        $(this).html("Processing ...");
        $(this).prop('disabled', function(i, v) { return !v; });

        var itemCount = $('.objectData').length;
        var itemsSynced = 0;

        // Loop over each item and post the object Data

        $('.objectData').each(function( index ){

            //console.log( index + ": " + $(this).val() );
            objectData = jQuery.parseJSON($(this).val());

            $.post(ajaxurl,
                {
                    action: 'sync_items',
                    posIndex: index,
                    syncImage: (objectData.images) ? (objectData.images[0].imageFileSized) ? objectData.images[0].imageFileSized : '' : '',
                    syncThumbnailImage: (objectData.images) ? (objectData.images[0].imageFileThumbnail) ? objectData.images[0].imageFileThumbnail : '' : '',
                    syncObjectId: (objectData.objectId) ? objectData.objectId : '',
                    syncTitle: (objectData.title) ? objectData.title : 'Untitled',
                    syncCopyright: (objectData.copyright) ? (objectData.copyright.description) ? objectData.copyright.description : '' : '',
                    syncPrice : (objectData.price) ? objectData.price : '',
                    syncStatus : (objectData.status) ? (objectData.status.name) ? objectData.status.name : '' : '',
                    syncCreator: (objectData.creators) ? (objectData.creators[0].displayName) ?  objectData.creators[0].displayName : '' : '' ,
                    syncWidth :  (objectData.size) ?(objectData.size.width) ? objectData.size.width : '' : '',
                    syncHeight : (objectData.size) ?(objectData.size.height) ? objectData.size.height : '' : '',
                    syncDepth : (objectData.size) ?(objectData.size.depth) ? objectData.size.depth : '' : '',
                    syncUnit : (objectData.size) ? (objectData.size.units) ? (objectData.size.units.value) ? objectData.size.units.value : '' : '' : '',
                    syncTags : (objectData.tags) ? objectData.tags :'',
                    syncCaption: (objectData.caption) ? objectData.caption : '',
                    syncCreationDateYear: (objectData.creationDate && objectData.creationDate.year) ? objectData.creationDate.year : '',
                    syncCreationDateMonth: (objectData.creationDate && objectData.creationDate.month) ? objectData.creationDate.month : '',
                    syncMedium: (objectData.medium && objectData.medium.name) ? objectData.medium.name : '',
                    syncAddress: (objectData.location) ? (objectData.location.address) ? objectData.location.address : '' : '',

                    listId: ($("#listId").val()) ? $("#listId").val() : "",
                    listName: ($("#listName").val()) ? $("#listName").val() : "",
                    pageType: ($("#pageType").val()) ? $("#pageType").val() : "",

                },


                function(response) {
                    itemsSynced++;

                    console.log("response : ". response);

                        if(itemsSynced == itemCount){
                            doneSync();
                            if (response !== -1 && response !== 0 && response !== false) {
                                $('#error').html("<h4>successfully synced</h4>");
                            }
                            else{
                                $('#error').html("<h4>Some Error occurred while syncing..</h4>")
                            }
                        }

                },'json')

        });


        doneSync = function(){
            $("#syncReport").html("Sync These Items");
            $("#syncReport").prop('disabled', function(i, v) { return !v; });
        }


    });
}(jQuery);