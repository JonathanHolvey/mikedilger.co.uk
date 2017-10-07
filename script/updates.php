<?php
include_once("../libraries/twitteroauth.php");
include_once("../libraries/iCalReader.php");

if (!is_dir("../cache"))
	mkdir("../cache");

class TwitterCache {
	function __construct($auth, $user, $count, $file) {
		$this->auth = $auth;
		$this->user = $user;
		$this->count = $count;
		$this->tweets = [];
		$this->modified = 0;
		$this->fromId = null;
		$this->cacheFile = $file;
		$this->cacheTime = 15 * 60;
	}

	function getTweets() {
		$this->loadTweets();
		if ($this->modified < time() - $this->cacheTime) {
			$this->tweets = array();
			// Construct API endpoint
			$url = "https://api.twitter.com/1.1/statuses/user_timeline.json?";
			$options = array(
				"screen_name" => $this->user,
				"count" => $this->count * 2,
				"include_rts" => "false",
				"exclude_replies" => "true"
			);

			// Fetch and process tweets
			$response = $this->auth->get($url . http_build_query($options));
			foreach (array_reverse($response) as $item) {
				$tweet = array();
				$tweet["time"] = strtotime($item->created_at);
				$tweet["text"] = $item->text;
				$tweet["id"] = $item->id_str;
				$tweet["user"] = $this->user;
				$tweet["url"] = "https://twitter.com/$this->user/statuses/$item->id_str";
				array_unshift($this->tweets, $tweet);
			}
			$this->tweets = array_slice($this->tweets, 0, $this->count);
			$this->cacheTweets();
		}
	}

	function newTweets() {
		$output = array();
		foreach ($this->tweets as $tweet) {
			if ($tweet["id"] > $this->fromId)
				array_unshift($output, $tweet);
			else
				break;
		}
		return $output;
	}

	// Cache tweets in JSON file
	private function cacheTweets() {
		$cache = array("tweets" => $this->tweets, "modified" => time());
		file_put_contents($this->cacheFile, json_encode($cache, true));
	}

	// Load cached tweets and metadata
	private function loadTweets() {
		if (is_file($this->cacheFile)) {
			$cache = json_decode(file_get_contents($this->cacheFile), true);
			$this->tweets = $cache["tweets"];
			$this->fromId = $this->tweets[0]["id"];
			$this->modified = $cache["modified"];
		}
	}
}

class CalendarCache {
	function __construct($url, $past, $future, $file) {
		$this->url = $url;
		$this->past = $past * 60 * 60 * 24;
		$this->future = $future * 60 * 60 * 24;
		$this->events = [];
		$this->modified = 0;
		$this->cacheFile = $file;
		$this->cacheTime = 15 * 60;
	}

	function getEvents() {
		$this->loadEvents();
		if ($this->modified < time() - $this->cacheTime) {
			$this->events = array();
			$future = time() + $this->future;
			$past = time() - $this->past;
			// Fetch and process events
			$response = new iCalReader($this->url);
			foreach($response->getEvents() as $item) {
				$event = array();
				$event["title"] = $item["SUMMARY"];
				$event["description"] = $item["DESCRIPTION"];
				$event["location"] = $item["LOCATION"];
				$event["start"] = strtotime($item["DTSTART;VALUE=DATE"]);
				$event["end"] = strtotime($item["DTEND;VALUE=DATE"]);
				$event["modified"] = strtotime($item["LAST-MODIFIED"]);
				$event["id"] = str_replace("@google.com", "", $item["UID"]);
				// Limit cached events to specified time period
				if ($event["start"] < $future and $event["end"] > $past)
					$this->events[] = $event;
			}
			$this->sortEvents();
			$this->cacheEvents();
		}
	}

	function newEvents() {
		$output = array();
		foreach ($this->events as $event) {
			if ($event["modified"] > $this->modified)
				$output = $event;
		}
		return $output;
	}

	// Sort events array chronologically by start date
	function sortEvents() {
		usort($this->events, array("CalendarCache", "eventCompare"));
	}
	static function eventCompare($a, $b) {
		if ($a["start"] < $b["start"])
			return -1;
		elseif ($a["start"] == $b["start"])
			return 0;
		elseif ($a["start"] > $b["start"])
			return 1;
	}

	// Cache events in JSON file
	private function cacheEvents() {
		$cache = array("events" => $this->events, "modified" => time());
		file_put_contents($this->cacheFile, json_encode($cache, true));
	}

	// Load cached events and metadata
	private function loadEvents() {
		if (is_file($this->cacheFile)) {
			$cache = json_decode(file_get_contents($this->cacheFile), true);
			$this->events = $cache["events"];
			$this->modified = $cache["modified"];
		}
	}
}

class KeyStore {
	function __construct($id) {
		$this->id = $id;
		$this->filename = "../cache/keystore-" . $id . ".json";
		$this->loadKeys();
	}
	private function cacheKeys() {
		if (!is_file($this->filename)) {
			$url = "https://dev.rocketchilli.com/keystore/" . $this->id;
			$data = file_get_contents($url);
			return ($data and file_put_contents($this->filename, $data));
		}
		return true;
	}
	private function loadKeys() {
		if ($this->cacheKeys()) {
			$data = json_decode(file_get_contents($this->filename), true);
			$this->service = $data["service"];
			$this->app = $data["app"];
			$this->keys = array_diff_key($data, array_flip(["id", "service", "app"]));
		}
	}
}
