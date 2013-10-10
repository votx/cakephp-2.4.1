<?php 
    $this->Html->script('jquery', array('inline' => false, 'fullBase' => true));
    $this->Html->css('fancybox/jquery.fancybox', array('inline' => false, 'fullBase' => true)); 
    $this->Html->script('fancybox/jquery.fancybox', array('inline' => false, 'fullBase' => true));
    
    echo $this->Html->link(
        'Back to Product Page',
        array('controller' => 'products', 'action' => 'page')
    ); 
    
    $auth = $this->Session->read('Auth.User');
?>
<br /><br />
<h1 style="font-weight: bold; font-size: 24px;"><?php echo h($product['Product']['title']); ?></h1>
<!--<small>Created: <?php echo $product['Product']['created']; ?></small>-->
<p>$<?php echo h($product['Product']['price']); ?></p>

<p><?php echo h($product['Product']['description']); ?></p>
<div class="images-view">
    <?php 
        foreach($images as $img):
            $path = $img['Productimages']['path'].'/'.$img['Productimages']['filename'];
    ?>
        <div class="img_classified" style="float: left; margin: 10px;">
            <a href="<?php echo $this->Html->url($path, true) ?>" onclick="return false" class="fancybox-thumb" rel="fancybox-thumb">
                <?php echo $this->Html->image($path, array('fullBase' => true, 'style' => 'width: 150px; height: 150px;')) ?>
            </a>
        </div>
    <?php endforeach; ?>
    <div style="clear: both;"></div>
</div>
<div class="comments">
    <h2>Comments</h2>
    <?php echo $this->Form->create('Comment'); ?>
        <fieldset style="margin: 0;">
            <?php 
                echo $auth == FALSE ? $this->Form->input('name') : $this->Form->input('name', array('value' => $auth['username'], 'readonly' => 'true'));
                echo $this->Form->input('comment', array('rows' => '3'));
            ?>
        </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
    
    
    <?php if(!empty($comments)): ?>
    
        <?php foreach($comments as $c): ?>
        
            <div class="m_comment">
                <span class="m_name"><?php echo $c['Comment']['name'] ?></span>
                <span class="m_created"><?php echo $c['Comment']['created_dt'] ?></span>
                <div style="clear: both;"></div>
                <p>
                    <?php echo $c['Comment']['comment'] ?>
                </p>
                
                
                
                <?php if($admin): ?>
                <a href="#" title="Delete Comment" class="remove_comment" id="<?php echo $c['Comment']['id'] ?>">
                    <?php echo $this->Html->image("close_cancel.png", array('fullBase' => true)); ?>                        
                </a>
                <?php endif; ?>
                
                
                
            </div>
        
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no_comment"><em>No comment at the moment for this product.</em></div>
    <?php endif; ?>
</div>
<style>
.comments{
    margin-top: 20px;
}

.m_comment{
    margin: 20px;
    padding: 10px;
    border: 1px solid grey;
}

.m_name{
    float: left;
}

.m_created{
    float: right;
}
</style>
<script>
$(function(){
    $(".fancybox-thumb").fancybox({
		prevEffect	: 'elastic',
		nextEffect	: 'elastic'
	});
    
    $('.remove_comment').click(function(e){
        e.preventDefault();
        var id = this.id;
        
        if (confirm('Remove comment?')) {
            $(this).parent('.m_comment').fadeOut(300, function() {
                $(this).remove(); 
                $('<input type="hidden" name="delete_comment" value="'+id+'" />').appendTo('#CommentViewForm');
                $('#CommentViewForm').submit();
            });
        }
    });
});

function deleteForm(e)
{
    
}
//

</script>