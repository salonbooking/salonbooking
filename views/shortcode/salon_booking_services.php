<?php
if(!$data['services']) return;
?>
<section class="sln-datashortcode sln-datashortcode--services">
	<h1 class="sln-datalist_title"><?php _e('Services','salon-booking-system'); ?></h1>
	<div class="sln-datalist <?php 
	if(isset($data['styled'])) echo 'sln-datalist--styled '; 
	if(isset($data['columns'])) echo 'sln-datalist--'.$data['columns'].'cols '; 	
	?>">
	<?php foreach ($data['services'] as $service) {
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
