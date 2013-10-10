<?php $this->Html->script('jquery', array('inline' => false, 'fullBase' => true)); ?>
<?php $this->Html->css('fancybox/jquery.fancybox', array('inline' => false, 'fullBase' => true)); ?>
<?php $this->Html->script('fancybox/jquery.fancybox', array('inline' => false, 'fullBase' => true)); ?>
<h1>Edit Product</h1>
<?php
    echo $this->Form->create('Product', array("enctype" => "multipart/form-data"));
    echo $this->Form->input('title');
    echo $this->Form->input('price');
    echo $this->Form->input('description', array('rows' => '3'));
    echo $this->Form->input('id', array('type' => 'hidden'));
?>
<div id="uploaded_images">
    <label for="published">Image(s)</label>
    <div style="clear: both;"></div>
    <?php 
        foreach($images as $img):
            $path = $img['Productimages']['path'].'/'.$img['Productimages']['filename'];
    ?>
        <div class="img_classified">
            <a style="vertical-align: top;" href="#" title="Delete" id="<?php echo $img['Productimages']['id'] ?>" class="remove_img">
                <?php echo $this->Html->image("close_cancel.png", array('fullBase' => true)); ?>                                
            </a>
            <a href="<?php echo $this->Html->url($path, true) ?>" onclick="return false" class="fancybox-thumb" rel="fancybox-thumb">
                <?php echo $this->Html->image($path, array('fullBase' => true, 'style' => 'width: 150px; height: 150px;')) ?>
            </a>
        </div>
    <?php endforeach; ?>
    <div style="clear: both;"></div>
</div>
<div id="upload_img">
    <label for="image" style="margin: 0;">Image(s)(Max 5)</label>
    <a href="#" title="Add New Image" id="add_img">
        Add New Image
	</a>
    <div class="input">
        <ul class="form_upload" id="images">
            <li>
                <input type="file" name="ProductImage[]" />
                <a href="#" onclick="deleteForm(this); return false" title="Delete" class="remove_input">
                    <?php echo $this->Html->image("close_cancel.png", array('fullBase' => true)); ?>                                
                </a>
            </li>
        </ul>
    </div>
</div>
<div id="delete_images"></div>
<?php echo $this->Form->end('Save Product'); ?>
<ul class="form_upload" style="display: none;">
    <li id="clone_form_upload">
        <input type="file" name="ProductImage[]" />
        <a href="#" onclick="deleteForm(this); return false" title="Delete" class="remove_input">
            <?php echo $this->Html->image("close_cancel.png", array('fullBase' => true)); ?>                        
        </a>
    </li>
</ul>
<style>
.form_upload{
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.form_upload li{
    margin-left: 0;
}
</style>
<script>
    $(function(){
    
        $(".fancybox-thumb").fancybox({
    		prevEffect	: 'elastic',
    		nextEffect	: 'elastic'
    	});
     
        drawNavigation();
    });
    
    $('#add_img').click(function(e){
        e.preventDefault();
        $clone = $('#clone_form_upload').clone().removeAttr('id');
        $clone.insertAfter('#images > li:last');
        drawNavigation();
    });
    
    $('a.remove_img').click(function(e){
        e.preventDefault();
        var id = this.id;
        
        if (confirm('Remove selected image?')) {
            $(this).parent('.img_classified').fadeOut(300, function() {
                $(this).remove(); 
                drawNavigation();
                $('<input type="hidden" name="delete_img[]" value="'+id+'" />').appendTo('#delete_images');
            });
            
        }
    });
    
    function drawNavigation() {
        var numForms = $("#images li").length;
        var numUploadedImg = $("#uploaded_images .img_classified").length;
        var totalImg = numForms + numUploadedImg;
        
        if(numUploadedImg > 4)
        {
            $('#upload_img').hide();
        }
        else
        {
            $('#upload_img').show();
            
            if (totalImg > 4) 
            {
                $('#add_img').hide();
            }
            else
            {
                $('#add_img').show();
            }
            
            if(numForms > 1)
            {
                $("#images .remove_input").show();
            }
            else
            {
                $("#images .remove_input").hide();
            }
            
            if(numUploadedImg < 1)
            {
                $("#uploaded_images").hide();
            }
        } 
    }
    
    function deleteForm(e) {
        $(e).closest('li').remove();
        drawNavigation();
    }
</script>