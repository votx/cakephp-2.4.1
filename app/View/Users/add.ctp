<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('User Registration'); ?></legend>
        <?php 
            echo $this->Form->input('username');
            echo $this->Form->input('password');
            echo $this->Form->input('role', array(
                    'options' => array('author' => 'Author')
                )
            );
        ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>