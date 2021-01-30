<?php include_once(__DIR__ . "/../script/functions.php") ?>

<div class="event" itemscope itemtype="http://schema.org/Event" data-id="<?= $event["id"] ?>">
	<h2 itemprop="name"><?= escape($event["title"]) ?></h2>
	<?php if (!empty($event["description"])): ?>
		<p itemprop="description"><?= escape($event["description"]) ?></p>
	<?php endif ?>
	<div class="time" itemprop="startDate" content="<?= date("c", $event["start"]) ?>">
		<?= date("l j F Y", (int)$event["start"]) ?></div>
	<?php if (!empty($event["location"])): ?>
		<div class="location" itemprop="location"><?= $event["location"] ?></div>
	<?php endif ?>
</div>
