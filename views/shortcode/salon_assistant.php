<?php
if(!$data['attendants']) return;
$service_repo             = $this->plugin->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
$all_service = $service_repo->getAll($service_repo);
?>

<!-- Versione di default, nuda e cruda -->
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
			<h3 class="sln-datalist__item__name"><?php echo $attendant->getName(); ?></h3>
			<div class="sln-datalist__item__image">
				<?php echo $thumb ?>
			</div>
			<p class="sln-datalist__item__description">
				<?php echo $attendant->getContent() ?>
			</p>
			<?php 
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
			<?php } ?>
			<div class="sln-datalist__item__actions">
				<a href="#nogo" class="sln-datalist__item__cta"><?php _e('Book now','salon-booking-system'); ?></a>
			</div>
		</div>		
	<?php } ?>
	</div>
</section>