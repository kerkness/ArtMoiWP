+function ($) {

    pageNumber = 1;

    $(document.body).on('click', '.thumbnail', function( event ){

        event.preventDefault();
        //alert( $(this).attr('objectId') );

        objectSelected = $(this).children('.objectSelected');

        if( objectSelected.val().length )
        {
            $(this).find('img-responsive').removeClass('.img-circle');

            objectSelected.val("");

        } else {

            objectSelected.val($(this).attr('objectId'));
            $(this).find('img-responsive').addClass('.img-circle');


        }

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

                    // Set the thumbnail objectId attribute
                    block.find('.thumbnail').attr('objectId', data.results[i].objectId);

                    // Find the objectSelected field and set the id.
                    block.find('.objectSelected').attr('id', data.results[i].objectId);

                    // Set the default value to not selected
                    block.find('.objectSelected').attr('value', '');

                    // Append this block
                    block.insertAfter('.omitem:last');

                }

                $("#loadMore").show();
                $("#loadingGif").hide();
            },'json');
    });

}(jQuery);