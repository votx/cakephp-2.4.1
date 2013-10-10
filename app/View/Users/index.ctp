<h1>Users</h1>
<?php 
    echo $this->Html->link(
        'Add New User',
        array('controller' => 'users', 'action' => 'add')
    ); 
?>
<?php if(!empty($users)): ?>
<table>
    <tr>
        <th>Id</th>
        <th>Username</th>
        <th>Role</th>
        <th>Action</th>
        <th>Created</th>
        <th>Last Login</th>
    </tr>

    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo $user['User']['id']; ?></td>
        <td>
            <?php 
                echo $this->Html->link(
                    $user['User']['username'],
                    array(
                        'controller' => 'users', 
                        'action' => 'edit', 
                        $user['User']['id']
                    )
                ); 
            ?>
        </td>
        <td><?php echo $user['User']['role']; ?></td>
        <td>
            <?php 
                #Delete btn POST
                echo $this->Form->postLink(
                    'Delete',
                    array(
                        'action' => 'delete', 
                        $user['User']['id']
                    ),
                    array('confirm' => 'Are you sure you want to delete this user?')
                );
                
                echo ' | ';
                
                #Edit btn ANCHOR
                echo $this->Html->link(
                    'Edit', 
                    array(
                        'action' => 'edit', 
                        $user['User']['id']
                    )
                ); 
            ?>
        </td>
        <td><?php echo $user['User']['created']; ?></td>
        <td><?php echo $user['User']['last_login']; ?></td>
    </tr>
    <?php endforeach; ?>
    <?php unset($user); ?>
</table>
<?php else: ?>
<div style="padding: 10px; border: 1px solid grey; text-align: center;">
No result.
</div>
<?php endif; ?>