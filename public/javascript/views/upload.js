// Dropzone settings
Dropzone.options.fileupload = {
    paramName: "file",
    maxFilesize: 100,
    maxFiles: 1,
    addRemoveLinks: true,
    dictRemoveFile: "Remove",
    dictCancelUpload: "Cancel",
    dictDefaultMessage: "Drop files here to upload",
    acceptedFiles: ".json, .zip",
    init: function () {
        this.on("success", function (file) {
            location.reload();
        });
    },
}

// Javascript for popups
$(document).ready(function(){
    // When the button is clicked, show the popup
    $(".upload-btn").click(function(){
       $(".upload-popup").show();
    });

    // When the close button is clicked, hide the popup
    $(".close-popup").click(function(){
       $(this).closest(".upload-popup").hide();
    });
 });