<?php
// Accrocco temporaneo, serve per non far diventare il file un papiello
$item = '<div class="sln-datalist__item">
			<h3 class="sln-datalist__item__name">Nome Servizio</h3>
			<div class="sln-datalist__item__image">
				<img src="http://via.placeholder.com/350x350">
			</div>
			<p class="sln-datalist__item__description">
				Donec euismod lacus eu ex auctor, et fringilla libero sodales. Nullam eu mi ut eros tincidunt scelerisque. Praesent iaculis, nisi vehicula eleifend molestie, justo elit auctor risus, id vestibulum purus velit hendrerit nisl. Cras porta ultricies tortor, eget vehicula enim ultricies a. Vestibulum finibus, turpis a ullamcorper tincidunt, ante lacus auctor odio, volutpat mollis quam nibh sed risus.
			</p>
			<div class="sln-datalist__item__list">
				<h5>Skills</h5>
				<ul>
					<li>Skill A</li>
					<li>Skill B</li>
					<li>Skill C</li>
					<li>Skill D</li>
				</ul>
			</div>
			<div class="sln-datalist__item__actions">
				<a href="#nogo" class="sln-datalist__item__cta">Book now</a>
			</div>
		</div>';
?>

<!-- Versione di default, nuda e cruda -->
<section class="sln-datashortcode sln-datashortcode--services">
	<h1 class="sln-datalist_title">Services</h1>
	<div class="sln-datalist">
		<div class="sln-datalist__item">
			<h3 class="sln-datalist__item__name">Nome Servizio</h3>
			<div class="sln-datalist__item__image">
				<img src="http://via.placeholder.com/350x350">
			</div>
			<p class="sln-datalist__item__description">
				Donec euismod lacus eu ex auctor, et fringilla libero sodales. Nullam eu mi ut eros tincidunt scelerisque. Praesent iaculis, nisi vehicula eleifend molestie, justo elit auctor risus, id vestibulum purus velit hendrerit nisl. Cras porta ultricies tortor, eget vehicula enim ultricies a. Vestibulum finibus, turpis a ullamcorper tincidunt, ante lacus auctor odio, volutpat mollis quam nibh sed risus.
			</p>
			<div class="sln-datalist__item__list">
				<h5>Skills</h5>
				<ul>
					<li>Skill A</li>
					<li>Skill B</li>
					<li>Skill C</li>
					<li>Skill D</li>
				</ul>
			</div>
			<div class="sln-datalist__item__actions">
				<a href="#nogo" class="sln-datalist__item__cta">Book now</a>
			</div>
		</div>
		<div class="sln-datalist__item">
			<h3 class="sln-datalist__item__name">Nome Servizio</h3>
			<div class="sln-datalist__item__image">
				<img src="http://via.placeholder.com/350x350">
			</div>
			<p class="sln-datalist__item__description">
				Curabitur ornare maximus enim sed sagittis. Pellentesque at justo lectus. Morbi iaculis nunc mauris, sit amet pulvinar elit eleifend sed. Maecenas risus leo, molestie non fermentum cursus, elementum nec urna. Phasellus id orci ut justo venenatis vulputate eu vel justo. Pellentesque facilisis sed metus vel pellentesque. Duis et convallis elit, eget vulputate ligula. Aliquam orci tortor, suscipit quis mauris ornare, venenatis suscipit eros. Mauris imperdiet ultricies consequat.
			</p>
			<div class="sln-datalist__item__list">
				<h5>Skills</h5>
				<ul>
					<li>Skill A</li>
					<li>Skill B</li>
					<li>Skill C</li>
					<li>Skill D</li>
				</ul>
			</div>
			<div class="sln-datalist__item__actions">
				<a href="#nogo" class="sln-datalist__item__cta">Book now</a>
			</div>
		</div>
		<div class="sln-datalist__item">
			<h3 class="sln-datalist__item__name">Nome Servizio</h3>
			<div class="sln-datalist__item__image">
				<img src="http://via.placeholder.com/350x350">
			</div>
			<p class="sln-datalist__item__description">
				Mauris in turpis lacus. Praesent sagittis sed turpis id molestie. Aliquam ac gravida felis. Fusce faucibus sem ligula, id fermentum massa lobortis sit amet. Donec sit amet fermentum eros. Phasellus sollicitudin quis urna non condimentum. Duis diam urna, commodo non feugiat eu, iaculis sed nibh.
			</p>
			<div class="sln-datalist__item__list">
				<h5>Skills</h5>
				<ul>
					<li>Skill A</li>
					<li>Skill B</li>
					<li>Skill C</li>
					<li>Skill D</li>
				</ul>
			</div>
			<div class="sln-datalist__item__actions">
				<a href="#nogo" class="sln-datalist__item__cta">Book now</a>
			</div>
		</div>
		<div class="sln-datalist_clearfix"></div>
	</div>
</section>

<!-- Versione stilata, va aggiunta al classse .sln-datalist--styled la div.sln-datalist -->
<section class="sln-datashortcode sln-datashortcode--assistants">
	<h1 class="sln-datalist_title">Services</h1>
	<div class="sln-datalist sln-datalist--styled">
		<?php echo str_repeat($item, 3) ?>
		<div class="sln-datalist_clearfix"></div>
	</div>
</section>

<!--
Varianti numero colonne (lavorano sia con la versione stilata che con quella base).
Le classsi da aggiungere al div.sln-datalist sono .sln-datalist--2cols, .sln-datalist--3cols o .sln-datalist--4cols
-->
<section class="sln-datashortcode sln-datashortcode--assistants">
	<h1 class="sln-datalist_title">Services</h1>
	<div class="sln-datalist sln-datalist--styled sln-datalist--2cols">
		<?php echo str_repeat($item, 4) ?>
		<div class="sln-datalist_clearfix"></div>
	</div>
</section>
<section class="sln-datashortcode sln-datashortcode--assistants">
	<h1 class="sln-datalist_title">Services</h1>
	<div class="sln-datalist sln-datalist--styled sln-datalist--3cols">
		<?php echo str_repeat($item, 5) ?>
		<div class="sln-datalist_clearfix"></div>
	</div>
</section>
<section class="sln-datashortcode sln-datashortcode--assistants">
	<h1 class="sln-datalist_title">Services</h1>
	<div class="sln-datalist sln-datalist--styled sln-datalist--4cols">
		<?php echo str_repeat($item, 7) ?>
		<div class="sln-datalist_clearfix"></div>
	</div>
</section>
