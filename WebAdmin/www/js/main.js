$(document).ready(function(){
    prepareSearchForm();
});

function prepareSearchForm(){
    var $form = $("#search-product-form");
    $form.find("input[name=barcode]").change(function(){
        console.log(':]');

        var value = $(this).value();
        $(".navbar-brand").text(value);
    });
}