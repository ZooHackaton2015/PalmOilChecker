$(document).ready(function(){
    //prepareSearchForm();

    prepareAjaxButtons();
});

function prepareSearchForm(){
    var $form = $("#products-search");
    $form.submit(function(e){
        e.preventDefault();
        return false;
    });

    $form.find("input[name=barcode]").change(function(){
        findProducts();
    });
}

function findProducts(){
    var value = $("#products-search").find("input[name=barcode]").val();
    var link = $("a#link").attr('href');
    link = link.replace('420', value);
    console.log(link);

    $.get(link, function(payload){
        console.log("payload got");
        $.nette.success(payload);
    });
}

function prepareAjaxButtons() {
    $('.products-table').on('click','a.ajax', function(e){
        e.preventDefault();
        var $this = $(this);
        var link = $this.attr('href');
        console.log(link);

        $.get(link, function(payload){
            console.log("payload got");
            $.nette.success(payload);
        });
    });


}