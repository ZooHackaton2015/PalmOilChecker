$(document).ready(function(){
    prepareSearchForm();
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