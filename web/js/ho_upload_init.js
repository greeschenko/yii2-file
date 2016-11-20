$(window).ready(function() {
    $('.ho_upload').each(function(index) {
        console.log($(this));
        new HOUpload($(this));
    });
});
