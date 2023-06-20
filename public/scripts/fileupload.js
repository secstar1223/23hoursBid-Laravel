$(document).ready(function() {
    var upload_dir = "before";
    // preventing page from redirecting
    $("html").on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
        $("h1").text("Drag here");
    });

    $("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

    // Drag enter
    $('.upload-area').on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $("h1").text("Drop");
    });

    // Drag over
    $('.upload-area').on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });

    $('#file').on("change",function(e){
        e.stopPropagation();
        e.preventDefault();
        var file = e.target.files[0];
        var fd = new FormData();
        fd.append('file', file);
        uploadData(fd,upload_dir);
    });

    // Drop
    $('.upload-area').on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var file = e.originalEvent.dataTransfer.files;
        var fd = new FormData();
        fd.append('file', file[0]);
        var strbuf = e.target.id;
        if(strbuf=='')
            strbuf = e.target.parentElement.id;
        if(strbuf=='uploadfile_before')
            upload_dir='before';
        else
            upload_dir='after';
        uploadData(fd,upload_dir);
    });

    // Open file selector on div click
    $("#uploadfile-before").click(function(){
        upload_dir="before";
        $("#file").click();
    });

    $("#uploadfile-after").click(function(){
        upload_dir="after";
        $("#file").click();
    });

    // file selected
    $("#file").change(function(){
        var fd = new FormData();

        var files = $('#file')[0].files[0];

        fd.append('file',files);

        uploadData(fd);
    });
});

// Sending AJAX request and upload file
// function uploadData(formdata){
//
//     $.ajax({
//         url: 'upload.php',
//         type: 'post',
//         data: formdata,
//         contentType: false,
//         processData: false,
//         dataType: 'json',
//         success: function(response){
//             addThumbnail(response);
//         }
//     });
// }

// Added thumbnail
function addThumbnail(data){
    $("#uploadfile h1").remove();
    var len = $("#uploadfile div.thumbnail").length;

    var num = Number(len);
    num = num + 1;

    var name = data.name;
    var size = convertSize(data.size);
    var src = data.src;

    // Creating an thumbnail
    $("#uploadfile").append('<div id="thumbnail_'+num+'" class="thumbnail"></div>');
    $("#thumbnail_"+num).append('<img src="'+src+'" width="100%" height="78%">');
    $("#thumbnail_"+num).append('<span class="size">'+size+'<span>');

}

// Bytes conversion
function convertSize(size) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (size == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
    return Math.round(size / Math.pow(1024, i), 2) + ' ' + sizes[i];
}
