$(document).ready(function(){
    prepareSearchForm();
});

function prepareSearchForm(){
    var $form = $("#search-product-form");
    alert($form.find("input[name=barcode]").size());
    $form.find("input[name=barcode]").change(function(){
        console.log(':]');

        var value = $(this).value();
        $(".navbar-brand").text(value);
    });
}