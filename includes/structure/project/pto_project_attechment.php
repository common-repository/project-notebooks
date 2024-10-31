<?php
global $post;
wp_enqueue_media();
?>
<div>
    <div class="all_attechments">
        <?php
        $all_attech = get_post_meta($post->ID, "cpt_project_all_attech", true);
        $all_attech = explode(",", $all_attech);
        if ($all_attech != "") {
            foreach ($all_attech as $imgname) {
                if ($imgname != "") {
                    $names = explode("/", $imgname);
                    $cnt = count($names);
                    $cnt = $cnt - 1;

                    ?>
                    <span class='attechment_name'><a href='<?php echo esc_html($imgname); ?>' download><?php echo esc_html($names[$cnt]); ?></a><a href='javascript:void(0)' class='remove_image' img='<?php echo esc_html($imgname); ?>'>(Remove)</a></span>
                    <?php
                }
            }
        }
        ?>
    </div>
    <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload" accept="image/*">
    <div class="all_filed_data">
        <?php
        if ($all_attech != "") {
            foreach ($all_attech as $imgname) {
                if ($imgname != "") {
                    ?>
                    <a href="">
                        <input type='hidden' name='pto_project_upload[]' value='<?php echo esc_html($imgname); ?>'>
                    </a>
                    <?php
                }
            }
        }
        ?>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#upload-btn').click(function(e) {
            e.preventDefault();
            var image = wp.media({
                title: 'Upload Image',
                    // mutiple: true if you want to upload multiple files at once
                    multiple: false
                }).open()
            .on('select', function(e) {
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first();
                    // We convert uploaded_image to a JSON object to make accessing it easier
                    // Output to the console uploaded_image
                    var image_url = uploaded_image.toJSON().url;
                    // Let's assign the url value to the input field
                    let i = 0;
                    $('.all_filed_data input').each(function() {
                        let file_name = jQuery(this).val();
                        let explod = file_name.split('/');
                        let cnt_get = explod.length;
                        cnt_get = cnt_get - 1;

                        if (uploaded_image.changed.filename == explod[cnt_get]) {
                            i = 1;
                        }
                    })
                    if (i == 0) {
                        $('.all_filed_data').append("<input type='hidden' name='pto_project_upload[]' value='" + uploaded_image.changed.url + "'>");
                        $('.all_attechments').append("<span class='attechment_name'>" + uploaded_image.changed.filename + "<a href='javascript:void(0)' class='remove_image' img='" + uploaded_image.changed.url + "'>(Remove)</a></span>");
                    }
                });
        });
    });
</script>
<?php
