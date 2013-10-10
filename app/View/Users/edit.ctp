<?php $auth = $this->Session->read('Auth.User'); ?>
<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Profile Edit'); ?></legend>
        <?php 
            echo $this->Form->input('username');
            echo $this->Form->input('new_password', array('type' => 'password', 'label' => 'Password'));
            echo $this->Form->input('id', array('type' => 'hidden'));
        ?>
        <div class="input text">
            <label for="UserUsername"><strong>Role</strong></label>
            <input type="text" value="<?php echo $auth['role'] ?>" readonly="true" />
        </div>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>