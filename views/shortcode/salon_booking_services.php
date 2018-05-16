<?php
if(!$data['services']) return;
$plugin = SLN_Plugin::getInstance();
?>
<section class="sln-datashortcode sln-datashortcode--services">	
	<div class="sln-datalist <?php 
	if(isset($data['styled'])) echo 'sln-datalist--styled '; 
	if(isset($data['columns'])) echo 'sln-datalist--'.$data['columns'].'cols '; 	
	?>">
	<?php foreach ($data['services'] as $service) {
		$thumb     = has_post_thumbnail($service->getId()) ?get_the_post_thumbnail(
        	$service->getId(),
        	'thumbnail'
    	) : '';	
	?>
		<div class="sln-datalist__item">
			<?php if(!$display || $display['name']){ ?>
			<h3 class="sln-datalist__item__name"><?php echo $service->getName() ?></h3>
			<?php } ?>
			<?php if(!$display || $display['image']){ ?>
			<div class="sln-datalist__item__image">
				<?php echo $thumb ?>
			</div>
			<?php } ?>
			<?php if(!$display || $display['description']){ ?>
			<p class="sln-datalist__item__description">
				<?php echo $service->getContent() ?>
			</p>
			<?php } ?>
			<div class="sln-datalist__item__info">
				<?php if(!$display || $display['duration']){ ?>
				<p class="sln-datalist__item__duration">
					<span><?php echo __('Duration', 'salon-booking-system')?>: </span>
					<strong><?php echo $service->getDuration()->format('H:i') ?></strong>
				</p>
				<?php } ?>
				<?php if(!$display || $display['price']){ ?>
				<p class="sln-datalist__item__price">
					<span><?php _e('Price','salon-booking-system');?>: </span>
					<strong><?php echo $plugin->format()->money($service->getPrice()) ?></strong>
				</p>
				<?php } ?>
			</div>		
			<?php if(!$display || $display['action']){ ?>
			<div class="sln-datalist__item__actions">
				<a href="<?php 	echo $data['booking_url']; ?>" class="sln-datalist__item__cta"><?php _e('Book now','salon-booking-system'); ?></a>
			</div>
			<?php } ?>
		</div>
	<?php } ?>			
		<div class="sln-datalist_clearfix"></div>
	</div>
</section>
