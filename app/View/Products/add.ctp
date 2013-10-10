<h1>Add Product</h1>
<?php 
echo $this->Form->create('Product', array("enctype" => "multipart/form-data"));
echo $this->Form->input('title');
echo $this->Form->input('price');
echo $this->Form->input('description', array('rows' => '3'));
//echo $this->Form->input('image[]', array("type" => "file"));
?>
    <div class="input file">
        <label for="ProductImage[]"><strong>Image(s)(Max 5)</strong></label>
        <a href="#" id="add_img">Add New Image</a>
        <ul class="form_upload" id="images">
            <li>
                <input type="file" name="ProductImage[]" />
                <a href="#" onclick="deleteForm(this); return false" title="Delete" class="remove_input">
                    <?php echo $this->Html->image("close_cancel.png", array('fullBase' => true)); ?>                                 
                </a>
            </li>
        </ul>
    </div>
<?php  
echo $this->Form->end('Save Product');
?>
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
        drawNavigation();
    });
    
    $('#add_img').click(function(e){
        e.preventDefault();
        $clone = $('#clone_form_upload').clone().removeAttr('id');
        $clone.insertAfter('#images > li:last');
        drawNavigation();
    });
    
    function drawNavigation() {
        var numForms = $("#images li").length;
        if (numForms > 1) {
            $("#images .remove_input").show();
            
            if (numForms > 4) 
            {
                $('#add_img').hide();
            }
            else
            {
                $('#add_img').show();
            }
        }
        else {
            $("#images .remove_input").hide();
        }
    }
    
    function deleteForm(e) {
        $(e).closest('li').remove();
        drawNavigation();
    }
</script>
