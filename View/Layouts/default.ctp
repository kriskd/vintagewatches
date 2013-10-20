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
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php $title_for_layout = 'Bruce\'s Vintage Watches'; ?>
		<?php $title_for_layout = isset($title) ? $title_for_layout . ' : ' . $title :  $title_for_layout; ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		//echo $this->Html->css('cake.generic');
		echo $this->Html->css('//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
		echo $this->Html->css('styles');
		
		echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');
		echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
		//echo $this->Html->script('https://js.stripe.com/v2/');
		//echo '<script type="text/javascript">Stripe.setPublishableKey("' . Configure::read('Stripe.TestPublishable') . '");</script>';

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		
		echo $this->Html->script('/js/build/scripts.min');
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<?php echo $this->Element('navigation'); ?>
	<div class="container">
		<div class="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
	<?php echo $this->Element('footer'); ?>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
