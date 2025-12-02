"use strict";

const { isset } = require("./util");

var SavoMartDropzoneInput = function () { };

SavoMartDropzoneInput.initDropzone = () => {

    if ($('[data-kt-dropzone-input="true"]').length) {
        var ipixDropzone = new Dropzone('[data-kt-dropzone-input="true"]', {
            url: $('#image-dropzone').data('action-url'),
            type: 'post',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            paramName: "file",
            maxFilesize: 10, // MB
            addRemoveLinks: true,
            acceptedFiles: '.png, .jpg, .jpeg',
            dictRemoveFile: "<i class='fas fa-times'></i>",
            accept: function (file, done) {
                if (file.name == "wow.jpg") {
                    done("Naha, you don't.");
                } else {
                    done();
                }
            }
        });
        ipixDropzone.on("removedfile", function (file) {
            if (file.serverId) {
                $.get($('#image-dropzone').data('delete-url') + '?file=' + file.response.fileName, 'id=' + file.serverId);
            }
        });
        ipixDropzone.on("success", function (file, response) {
            file.response = response;
            file.serverId = response.id;
            $(file.previewElement).attr('data-server-id', file.serverId);
            file.response = response;
            $(file.previewElement).find('.dz-details').html(response.form);
        });
        if ($('#image-dropzone[data-fetchable="true"]').length) {
            $.ajax({
                "url": $('#image-dropzone').data('fetch-url'),
                "type": "GET",
                "success": function (data) {
                    $.each(data, function (key, value) {
                        var mockFile = { name: value.url, serverId: value.id, response: { fileName: value.file } };
                        ipixDropzone.emit("addedfile", mockFile);
                        ipixDropzone.options.thumbnail.call(ipixDropzone, mockFile, value.url);
                        ipixDropzone.files.push(mockFile);
                        ipixDropzone.emit("complete", mockFile);
                        $(mockFile.previewElement).attr('data-server-id', value.id);
                        $(mockFile.previewElement).attr('data-link', value.link);
                        $(mockFile.previewElement).attr('data-title', value.title);
                        $(mockFile.previewElement).find('.dz-details').html(value.form);
                    });
                }
            });
        }

        $(document).on('click', '#image-dropzone[data-content="true"] .dz-preview', function () {
            var image = $(this);
            var id = image.data('server-id');
            if (id != undefined) {
                var link = (image.data('link')) != null ? image.data('link') : '';
                var title = (image.data('title')) != null ? image.data('title') : '';
                Swal.fire({
                    html: 'Link <input type="url" id="swal-url" class="swal2-input" value="' + link + '" >' +
                        'Title <input type="text" id="swal-title" class="swal2-input" value="' + title + '">',
                    showCancelButton: false,
                    closeOnConfirm: true,
                }).then(function (data, inputValue) {
                    var link = $('#swal-url').val();
                    var title = $('#swal-title').val();
                    $.ajax({
                        url: $('#image-dropzone').data('link-update-url'),
                        type: "post",
                        data: {
                            _token: _token,
                            id: id,
                            link: link,
                            title: title,
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == true) {
                                image.attr('data-link', link);
                                image.attr('data-title', title);
                                Swal.fire({
                                    icon: "success",
                                    text: "Link Updated",
                                });
                            }
                        },
                    });
                });
            }
        });
    }
}

SavoMartDropzoneInput.init = function () {
    SavoMartDropzoneInput.initDropzone();
};

SavoMartUtil.onDOMContentLoaded(function () {
    SavoMartDropzoneInput.init();
});

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = SavoMartDropzoneInput;
}