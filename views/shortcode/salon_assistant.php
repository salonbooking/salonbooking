<?php
if(!$data['attendants']) return;
$service_repo             = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
$all_service = $service_repo->getAll($service_repo);
?>
<section class="sln-datashortcode sln-datashortcode--assistants">
	<h1 class="sln-datalist_title"><?php _e('Assistants','salon-booking-system'); ?></h1>
	<div class="sln-datalist <?php 
	if(isset($data['styled'])) echo 'sln-datalist--styled '; 
	if(isset($data['columns'])) echo 'sln-datalist--'.$data['columns'].'cols '; 	
	?>">
	<?php foreach ($data['attendants'] as $attendant) {
		$thumb     = has_post_thumbnail($attendant->getId()) ?get_the_post_thumbnail(
                $attendant->getId(),
                'thumbnail'
            ) : '';	
	?>
		<div class="sln-datalist__item">
			<?php if(!$display || $display['name']){ ?>
			<h3 class="sln-datalist__item__name"><?php echo $attendant->getName(); ?></h3>
			<?php } ?>
			<?php if(!$display || $display['image']){ ?>
			<div class="sln-datalist__item__image">
				<?php echo $thumb ?>
			</div>
			<?php } ?>
			<?php if(!$display || $display['description']){ ?>
			<p class="sln-datalist__item__description">
				<?php echo $attendant->getContent() ?>
			</p>
			<?php } ?>
			<?php 
			if(!$display || $display['skills']){
			$services = $attendant->getServices() ?: $all_service;
			if($services){
			?>
			<div class="sln-datalist__item__list">
				<h5><?php _e('Skills','salon-booking-system'); ?></h5>

				<ul>
					<?php foreach ($services as $service) { 						
						echo '<li>'.$service->getTitle().'</li>';
					}?>					
				</ul>
			</div>
			<?php }} ?>
			<?php if(!$display || $display['action']){ ?>
			<div class="sln-datalist__item__actions">
				<a href="<?php 	echo get_the_permalink($this->getPlugin()->getSettings()->getThankyouPageId()); ?>" class="sln-datalist__item__cta"><?php _e('Book now','salon-booking-system'); ?></a>
			</div>
			<?php } ?>
		</div>		
	<?php } ?>
		<div class="sln-datalist_clearfix"></div>
	</div>
</section>