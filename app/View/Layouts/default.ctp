<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$auth = $this->Session->read('Auth.User');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
        
        //Load jquery
        echo $this->Html->script('jquery');
	?>
</head>
<body>
	<div id="container">
        
        <!--
		<div id="header">
			<h1><?php //echo $this->Html->link($cakeDescription, 'http://cakephp.org'); ?></h1>
		</div>
        -->
        <style>
            #main_menu ul{
                padding: 0;
                margin: 0;
                list-style-type: none;
            }
            #main_menu ul li{
                float: left;
            }
            
            #main_menu{
                float: left;
            }
            
            #login_menu{
                float: right;
            }
            
            #login_menu, #main_menu{
                padding: 10px;
                margin: 10px;
            }
            
            #login_menu a, #main_menu a{
                color: white;
                text-decoration: none;    
            }
            
            #login_menu a:hover, #main_menu a:hover{
                color: #81DAF5;
                text-decoration: underline;
            }
            
            .normal_float_menu{
                padding: 0;
                margin: 0;
                list-style-type: none;
                float: left;
                border: 1px solid grey;
                padding: 10px;
                margin-bottom: 10px;
                margin-right: 20px;
            }
            .normal_float_menu li{
                float: left;
            }
        </style>
        <div id="main_menu">
            <ul>
                <li>
                    <?php
                        echo $this->Html->link('Home', array('controller' => 'products', 'action' => 'page'));
                    ?>
                </li>
            </ul>
            <div style="clear: both;"></div>
        </div>
        <div id="login_menu">
            <?php if ($auth): ?>
                Welcome, <?php echo $auth['username'] ?> |
                <?php                    
                    echo $this->Html->link(
                        'Edit Profile',
                        array('controller' => 'users', 'action' => 'edit', $auth['id'])
                    );                    
                ?>
                |
                <?php
                    echo $this->Html->link(
                        'Logout',
                        array('controller' => 'users', 'action' => 'logout')
                    );
                ?>
            <?php else: ?>
                <?php
                    echo $this->Html->link(
                        'Login',
                        array('controller' => 'users', 'action' => 'login')
                    );
                    echo ' | ';
                    echo $this->Html->link(
                        'Register',
                        array('controller' => 'users', 'action' => 'add')
                    );
                ?>
            <?php endif; ?>
        
        </div>
        <div style="clear: both;"></div>
		<div id="content">
            
            <?php if($auth): ?>
                <ul class="normal_float_menu">
                    <li><?php echo $this->Html->link('Product List', array('controller' => 'products', 'action' => 'user')); ?></li>
                    <li>|</li>
                    <li><?php echo $this->Html->link('Add New Product', array('controller' => 'products', 'action' => 'add')); ?></li>
                </ul>
                <?php if($auth['role'] === 'admin'): ?>
                    <ul class="normal_float_menu">
                        <li><?php echo $this->Html->link('User List', array('controller' => 'users', 'action' => 'index')); ?></li>
                        <li>|</li>
                        <li><?php echo $this->Html->link('Add New User', array('controller' => 'users', 'action' => 'add')); ?></li>
                    </ul>
                <?php endif; ?>
                <div style="clear: both;"></div>
            <?php endif; ?>
            
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
			?>
		</div>
	</div>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
