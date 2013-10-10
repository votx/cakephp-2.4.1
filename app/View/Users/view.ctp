<table>
    <tr>
        <td>Username</td>
        <td>: <?php echo h($user['User']['username']); ?></td>
    </tr>
    <tr>
        <td>Role</td>
        <td>: <?php echo h($user['User']['role']); ?></td>
    </tr>
    <tr>
        <td>Created</td>
        <td>: <small><?php echo $user['User']['created']; ?></small></td>
    </tr>
    <tr>
        <td>Last Login</td>
        <td>: <small><?php echo $user['User']['last_login']; ?></small></td>
    </tr>
</table>