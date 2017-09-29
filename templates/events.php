<?php include_once(__DIRECTORY__ . "/../script/functions.php");
foreach ($events as $event): ?>

<div class="event">
	<h2><?= escape($event["title"]) ?></h2>
	<div class="time"><?= date("l j F Y", (int)$event["start"]) ?></div>
</div>

<?php endforeach ?>
