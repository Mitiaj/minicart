$(document).ready(function(){
    $('form#searchForm').submit(function(e){
        e.preventDefault();
        return false;
    });

    $('form#searchForm input[type=text]').keyup(function(){
        var data = $('form#searchForm').serialize();
        $.ajax({
            type: "GET",
            url: "http://dev/local/minicart/site/ajaxgetproducts",
            data: data,
            context: $(this),
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status +" " +thrownError);
                return false;
            },
            success: function(data){
                console.log(data);
                $('.searchBox').remove();
                var pos = $(this).position();
                $('body').append('<div class="searchBox"> </div>');
                $('.searchBox').html(data);
                $('.searchBox').css({'position':'absolute', 'top' : pos.top + 30, 'left': pos.left });

            }
        });
    });
    $('form#searchForm input[type=text]').blur(function(){
        //$('.searchBox').remove();
    });
});

