// Sweet Alert Initialize
const swalInit = swal.mixin({
    buttonsStyling: false,
    customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-light",
        denyButton: "btn btn-light",
        input: "form-control",
    },
});

var DateTimePickers = (function () {
    const _componentDatepicker = function () {
        if (typeof Datepicker == "undefined") {
            console.warn("Warning - datepicker.min.js is not loaded.");
            return;
        }

        const dpBasicElements = document.querySelectorAll(".datepicker-basic");

        if (dpBasicElements.length > 0) {
            dpBasicElements.forEach(function (element) {
                new Datepicker(element, {
                    container: ".content-inner",
                    buttonClass: "btn",
                    prevArrow: document.dir === "rtl" ? "&rarr;" : "&larr;",
                    nextArrow: document.dir === "rtl" ? "&larr;" : "&rarr;",
                    format: "mm/dd/yyyy",
                });
            });
        }
    };

    return {
        init: function () {
            _componentDatepicker();
        },
    };
})();

// Function to deletion record using SweetAlert and AJAX
function deleteRecord(url, data, rowids) {
    var csrfToken = $('meta[name="csrfToken"]').attr("content");

    swalInit
        .fire({
            title: "Are you sure you want to delete selected records?",
            text: "You will not be able to recover these records after deletion.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, I am sure",
            cancelButtonText: "No, Cancel it",
        })
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                    },
                    success: function (response) {
                        swalInit
                            .fire("Deleted!", response.message, "success")
                            .then(() => {
                                // Check if the rowids is an array (for multiple deletions)
                                if (Array.isArray(rowids)) {
                                    rowids.forEach(function (id) {
                                        $("#" + id)
                                            .closest("tr")
                                            .remove();
                                    });
                                } else {
                                    // Handle single row deletion
                                    $("#" + rowids)
                                        .closest("tr")
                                        .remove();
                                }
                            });
                    },
                    error: function (xhr) {
                        swalInit.fire(
                            "Error!",
                            "There was a problem deleting the record(s).",
                            "error"
                        );
                        console.error(xhr.responseText);
                    },
                });
            }
        });
}

function initializeUploadImage() {
    var uploadedFiles = [];

    $("#uploadFilesInput").change(function (e) {
        if (this.files && this.files[0]) {
            var formData = new FormData();
            for (var i = 0; i < this.files.length; i++) {
                var file = this.files[i];
                formData.append("file[]", file);
                // formData.append('uploadedFiles[]', file);
            }
            // formData.append('file', this.files[0]);
            formData.append("_csrfToken", csrfToken);

            $.ajax({
                url: baseUrl + "Users/uploadMultipleTempFiles",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }

                    response.data.filename.forEach(function (item) {
                        uploadedFiles.push(item);
                        $("#uploadFiles").val(uploadedFiles.join(","));

                        var fileExtension = item.split(".").pop().toLowerCase();
                        const previewUrl = getPreviewUrl(fileExtension, item);

                        // Display the uploaded image in the preview container
                        $("#image-preview-container").append(`
                        <div class="uploaded-image" data-filename="${item}" style="position: relative; display: inline-block;margin-right:5px">
                            <img src="${previewUrl}" alt="uploaded image" class="img-thumbnail" width="100" />
                            <button type="button" class="btn btn-danger btn-sm remove-image" data-filename="${item}" style="position: absolute; top: 5px; right: 5px; padding: 2px 5px;">X</button>
                        </div>
                    `);
                    });
                },
                error: function (response) {
                    console.error("Upload failed:", response);
                },
            });
        }
    });

    $("#image-preview-container").on("click", ".remove-image", function () {
        var filename = $(this).data("filename");
        $.ajax({
            url: baseUrl + "Users/deleteTempFiles",
            type: "POST",
            data: {
                id: filename,
                _csrfToken: csrfToken,
            },
            success: function (response) {
                if (typeof response === "string") {
                    response = JSON.parse(response);
                }
                console.log(response.message);

                // Remove the file from the uploadedFiles array
                uploadedFiles = uploadedFiles.filter(function (value) {
                    return value !== filename;
                });
                $("#uploadFiles").val(uploadedFiles.join(","));

                // Remove the preview element
                $(`[data-filename="${filename}"]`).remove();
            },
            error: function (response) {
                console.error("Failed to delete file:", response);
            },
        });
    });
}

function getPreviewUrl(fileExtension, filename) {
    if (["jpg", "jpeg", "png", "gif"].includes(fileExtension)) {
        return baseUrl + `/temp_uploads/${filename}`;
    } else if (fileExtension === "pdf") {
        return baseUrl + "/assets/images/pdf-icon.png";
    }
    return baseUrl + "/assets/images/avatar.jpg";
}

function deleteUploadedFile() {
    var uploadedFiles = $("#uploadFiles").val()
        ? $("#uploadFiles").val().split(",")
        : [];

    $("#image-preview-container").on(
        "click",
        ".remove-image-during-edit",
        function () {
            var filename = $(this).data("filename");
            var id = $(this).data("id");
            var moduleName = $(this).data("module");
            $.ajax({
                url: baseUrl + moduleName + "/deleteFiles",
                type: "POST",
                data: {
                    file: filename,
                    id: id,
                    _csrfToken: csrfToken,
                },
                success: function (response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }
                    console.log(response.message);

                    // Remove the file from the uploadedFiles array
                    uploadedFiles = uploadedFiles.filter(function (value) {
                        return value !== filename;
                    });
                    // $('#uploadFiles').val(uploadedFiles.join(','));

                    // Remove the preview element
                    $(`.uploaded-image[data-filename="${filename}"]`).remove();
                },
                error: function (response) {
                    console.error("Failed to delete file:", response);
                },
            });
        }
    );
}

function updateStatus() {
    $(".update-status").on("click", function (e) {
        e.preventDefault(); // Prevent default form submission

        var form = $(this).closest("form");
        form.find("#status_input").val($(this).attr("data-status"));

        // Send the AJAX request to update the status
        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: form.serialize(),
            headers: {
                "X-CSRF-Token": csrfToken, // Ensure CSRF token is set here
            },
            success: function (response) {
                if (response.success) {
                    alert(response.message || "Status updated successfully");
                    location.reload(); // Reload page to reflect changes
                } else {
                    alert(response.message || "Failed to update status");
                }
            },
            error: function () {
                alert("An error occurred while updating the status.");
            },
        });
    });
}

function initializeUploadImage() {
    var uploadedFiles = [];

    $("#uploadFilesInput").change(function (e) {
        if (this.files && this.files[0]) {
            var formData = new FormData();
            for (var i = 0; i < this.files.length; i++) {
                var file = this.files[i];
                formData.append("file[]", file);
                // formData.append('uploadedFiles[]', file);
            }
            // formData.append('file', this.files[0]);
            formData.append("_csrfToken", csrfToken);

            $.ajax({
                url: baseUrl + "Users/uploadMultipleTempFiles",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }

                    response.data.filename.forEach(function (item) {
                        uploadedFiles.push(item);
                        $("#uploadFiles").val(uploadedFiles.join(","));

                        var fileExtension = item.split(".").pop().toLowerCase();
                        const previewUrl = getPreviewUrl(fileExtension, item);

                        // Display the uploaded image in the preview container
                        $("#image-preview-container").append(`
                        <div class="uploaded-image" data-filename="${item}" style="position: relative; display: inline-block;margin-right:5px">
                            <img src="${previewUrl}" alt="uploaded image" class="img-thumbnail" width="100" />
                            <button type="button" class="btn btn-danger btn-sm remove-image" data-filename="${item}" style="position: absolute; top: 5px; right: 5px; padding: 2px 5px;">X</button>
                        </div>
                    `);
                    });
                },
                error: function (response) {
                    console.error("Upload failed:", response);
                },
            });
        }
    });

    $("#image-preview-container").on("click", ".remove-image", function () {
        var filename = $(this).data("filename");
        $.ajax({
            url: baseUrl + "Users/deleteTempFiles",
            type: "POST",
            data: {
                id: filename,
                _csrfToken: csrfToken,
            },
            success: function (response) {
                if (typeof response === "string") {
                    response = JSON.parse(response);
                }
                console.log(response.message);

                // Remove the file from the uploadedFiles array
                uploadedFiles = uploadedFiles.filter(function (value) {
                    return value !== filename;
                });
                $("#uploadFiles").val(uploadedFiles.join(","));

                // Remove the preview element
                $(`[data-filename="${filename}"]`).remove();
            },
            error: function (response) {
                console.error("Failed to delete file:", response);
            },
        });
    });
}

function getPreviewUrl(fileExtension, filename) {
    if (["jpg", "jpeg", "png", "gif"].includes(fileExtension)) {
        return baseUrl + `/temp_uploads/${filename}`;
    } else if (fileExtension === "pdf") {
        return baseUrl + "/assets/images/pdf-icon.png";
    }
    return baseUrl + "/assets/images/avatar.jpg";
}

function deleteUploadedFile() {
    var uploadedFiles = $("#uploadFiles").val()
        ? $("#uploadFiles").val().split(",")
        : [];

    $("#image-preview-container").on(
        "click",
        ".remove-image-during-edit",
        function () {
            var filename = $(this).data("filename");
            var id = $(this).data("id");
            var moduleName = $(this).data("module");
            $.ajax({
                url: baseUrl + moduleName + "/deleteFiles",
                type: "POST",
                data: {
                    file: filename,
                    id: id,
                    _csrfToken: csrfToken,
                },
                success: function (response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }
                    console.log(response.message);

                    // Remove the file from the uploadedFiles array
                    uploadedFiles = uploadedFiles.filter(function (value) {
                        return value !== filename;
                    });
                    // $('#uploadFiles').val(uploadedFiles.join(','));

                    // Remove the preview element
                    $(`.uploaded-image[data-filename="${filename}"]`).remove();
                },
                error: function (response) {
                    console.error("Failed to delete file:", response);
                },
            });
        }
    );
}

function updateStatus() {
    $(".update-status").on("click", function (e) {
        e.preventDefault(); // Prevent default form submission

        var form = $(this).closest("form");
        form.find("#status_input").val($(this).attr("data-status"));

        // Send the AJAX request to update the status
        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: form.serialize(),
            headers: {
                "X-CSRF-Token": csrfToken, // Ensure CSRF token is set here
            },
            success: function (response) {
                if (response.success) {
                    alert(response.message || "Status updated successfully");
                    location.reload(); // Reload page to reflect changes
                } else {
                    alert(response.message || "Failed to update status");
                }
            },
            error: function () {
                alert("An error occurred while updating the status.");
            },
        });
    });
}
