<?php include_once(__DIR__ . "/../script/functions.php") ?>

<div class="event" data-id="<?= $event["id"] ?>">
	<h2><?= escape($event["title"]) ?></h2>
	<div class="time"><?= date("l j F Y", (int)$event["start"]) ?></div>
</div>
