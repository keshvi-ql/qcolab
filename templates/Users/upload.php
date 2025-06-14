<?php $this->start('css'); ?>
<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
<?php $this->end(); ?>

<button type="button" id="upload_button" class="btn btn-primary">Change Profile Image</button>
<input type="file" name="image" class="image" id="upload_image" style="display:none" />

<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image Before Upload</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img src="" id="sample_image" />
                        </div>
                        <div class="col-md-4">
                            <div class="preview"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="zoomin" class="btn btn-primary">Zoom In</button>
                <button type="button" id="zoomout" class="btn btn-primary">Zoom Out</button>
                <button type="button" id="rotateleft" class="btn btn-primary">rotate Left</button>
                <button type="button" id="rotateright" class="btn btn-primary">rotate Right</button>
                <button type="button" id="scalex" class="btn btn-primary">Scale X</button>
                <button type="button" id="scaley" class="btn btn-primary">Scale Y</button>
                <br><br>
                <button type="button" id="aspres169" class="btn btn-primary">16:9</button>
                <button type="button" id="aspres43" class="btn btn-primary">4:3</button>
                <button type="button" id="aspres11" class="btn btn-primary">1:1</button>
                <button type="button" id="aspres23" class="btn btn-primary">2:3</button>
                <button type="button" id="aspresfree" class="btn btn-primary">free</button>
                <button type="button" id="crop" class="btn btn-primary">Crop</button>
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


<?php $this->start('script'); ?>
<script src="https://unpkg.com/dropzone"></script>
<script src="https://unpkg.com/cropperjs"></script>
<script>
    $(document).ready(function() {
        var csrfToken = $('meta[name="csrfToken"]').attr("content");

        var $modal = $('#modal');

        var image = document.getElementById('sample_image');

        var cropper;

        $('#upload_button').click(function() {
            $('#upload_image').click();
        });

        $('#upload_image').change(function(event) {
            var files = event.target.files;

            if (files && files.length > 0) {
                reader = new FileReader();
                reader.onload = function(event) {
                    image.src = reader.result;
                    $modal.modal('show');
                };
                reader.readAsDataURL(files[0]);
            }
        });

        $modal.on('shown.bs.modal', function() {
            cropper = new Cropper(image, {
                aspectRatio: NaN,
                viewMode: 0,
                preview: '.preview',
                movable: true,
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        $('#zoomin').click(function() {
            cropper.zoom(0.1);
        });
        $('#zoomout').click(function() {
            cropper.zoom(-0.1);
        });

        $('#rotateleft').click(function() {
            cropper.rotate(-45);
        });

        $('#rotateright').click(function() {
            cropper.rotate(45);
        });

        $('#scalex').click(function() {
            cropper.scaleX(-1);
        });
        $('#scaley').click(function() {
            cropper.scaleY(-1);
        });
        $('#aspres169').click(function() {
            cropper.setAspectRatio(16 / 9);
        });
        $('#aspres43').click(function() {
            cropper.setAspectRatio(4 / 3);
        });
        $('#aspres11').click(function() {
            cropper.setAspectRatio(1 / 1);
        });
        $('#aspres23').click(function() {
            cropper.setAspectRatio(2 / 3);
        });
        $('#aspresfree').click(function() {
            cropper.setAspectRatio(NaN);
        });

        $('#crop').click(function() {
            canvas = cropper.getCroppedCanvas();

            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;

                    $.ajax({
                        url: '<?= $this->Url->build(['controller' => 'Users', 'action' => 'saveCropProfileImage']) ?>',
                        method: 'POST',
                        headers: {
                            'X-CSRF-Token': csrfToken
                        },
                        data: {
                            image: base64data,
                            id: 8
                        },
                        success: function(data) {
                            $modal.modal('hide');
                            if (typeof data === "string") {
                                data = JSON.parse(data);
                            }
                            $('#uploaded_image').attr('src', data.data.imageUrl);
                        }
                    });
                };
            });
        });
    });
</script>

<?php $this->end(); ?>