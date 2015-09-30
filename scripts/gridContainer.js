+function ($) {

    pageNumber = 1;

    $(document.body).on('click', '.thumbnail', function( event ){
        // prevent default click behavour
        event.preventDefault();

        // get the objectSelected checkbox element
        objectSelected = $(this).find('.objectSelected');
        //dataHolder = $(this).find('.objectData');

        objectData = jQuery.parseJSON( $(this).find('.objectData').val());

        // Toggle the check box
        objectSelected.prop("checked", ! objectSelected.prop("checked"));

        $.post(ajaxurl,
            {
                action: 'sync_creation',
                syncImage: (objectData.images[0].imageFileSized) ? objectData.images[0].imageFileSized : '',
                syncObjectId: (objectData.objectId) ? objectData.objectId : '',
                syncTitle: (objectData.title) ? objectData.title : '',
                syncCaption: (objectData.caption) ? objectData.caption : '',
                syncLocation: (objectData.creationDate.year) ? objectData.creationDate.year : '',

                //syncArtist: objectData.user.creators[0].displayName,
                syncMedium: (objectData.medium.name) ? objectData.medium.name : '',
            },
            function(response) {

                alert(response);

            },'json');
    });

    $("#loadMore").click(function( event )
    {
        event.preventDefault();
        $("#loadMore").hide();
        $("#loadingGif").show();

        pageNumber = pageNumber + 1;
        apiKey = $("#hiddenKey").val();

        //alert(pageNumber);

        $.post('http://api.omona.me/1.0/creation/user',
            {
                p: pageNumber,
                key: apiKey,
            },
            function(data,status) {

                if (data.error) {
                    alert(data.error);
                }

                var leng = data.results.length;

                for (i = 0; i < leng; i++) {

                    // Get the last element with the class .omitem
                    block = $('.omitem:last').clone();

                    // Find the thumbnail and add src value
                    block.find('.img-responsive').attr('src',data.results[i].images[0].imageFileThumbnail);

                    // Find the objectSelected field and set the id.
                    block.find('.objectSelected').attr('id', data.results[i].objectId);

                    // Set the default value to not selected
                    block.find('.objectSelected').attr('value', data.results[i].objectId);

                    // Append this block
                    block.insertAfter('.omitem:last');

                }

                $("#loadMore").show();
                $("#loadingGif").hide();
            },'json');
    });

}(jQuery);