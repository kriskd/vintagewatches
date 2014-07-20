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
<html class="no-js" lang="en-US">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php $title_for_layout = 'Bruce\'s Vintage Watches'; ?>
		<?php $title_for_layout = isset($title) ? $title_for_layout . ' : ' . $title :  $title_for_layout; ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
        $description = 'Bruce\'s Vintage Wristwatches sells vintage watches,
        with an emphasis on wristwatches. Also offered are many brands of
        chronographs and other complicated watches, such as triple date, and
        triple date moonphase. Emphasis is on affordable vintage watches in
        the $100 to $1,000 price range. All watches for sale come with a 7-day
        unconditional money-back guarantee. The company also offers trades,
        or will buy your vintage watches outright. Most major credit/debit
        cards are accepted.';
        $description = empty($watch['Watch']['description']) ? $description : $this->Text->truncate(strip_tags($watch['Watch']['description']), 150, array('exact' => false));
		echo $this->html->meta('description', $description);
		echo $this->Html->meta('keywords', $allBrands);
		echo $this->Html->meta('icon');

		echo $this->Html->css('//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css');
        //echo $this->Html->css('/css/build/jquery-ui.min');
		echo $this->Html->css('/css/build/styles.min.css?v='.filemtime(WWW_ROOT.'/css/build/styles.min.css'));
		
		echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
        //echo $this->Html->script('/js/build/jquery-1.11.1.min');
		echo $this->Html->script('/js/build/scripts.min.js?v='.filemtime(WWW_ROOT.'/js/build/scripts.min.js'));
		
		//echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<?php if ($hideAnalytics == false): ?>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		      
			ga('create', 'UA-45551384-1', 'brucesvintagewatches.com');
			ga('send', 'pageview');
		</script>
	<?php endif; ?>
</head>
<?php // show-fat-footer is just a dummy class show html is valid ?>
<body class="<?php echo $hideFatFooter==true ? 'hide-fat-footer' : 'show-fat-footer'; ?>">
	<?php echo $this->Element('navigation'); ?>
    <div class="no-script">
        You do not have Javascript enabled in your web browser, which
        is required by this site. Please enable it now.
        <?php echo $this->Html->link('Learn how to enable Javacript.',
                         'http://enable-javascript.com/',
                         array(
                           'target' => '_blank')
                    ); ?>
    </div>
	<?php if (stage() == true): ?>
		<div class="stage">
			Staging Site
		</div>
	<?php endif; ?>
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
