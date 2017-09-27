<?php
include_once("../libraries/twitteroauth.php");
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
			// Construct API endpoint
			$url = "https://api.twitter.com/1.1/statuses/user_timeline.json?";
			$options = array(
				"screen_name" => $this->user,
				"count" => $this->count,
				"include_rts" => "false",
				"exclude_replies" => "true"
			);
			// Limit response to uncached tweets
			if (!is_null($this->fromId))
				$options["since_id"] = $this->fromId;

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
			$this->cacheTweets();
		}
	}

	// Print new tweets as JSON
	function sendNew() {
		$output = array("new-tweets" => [], "modified" => time());
		foreach ($this->tweets as $tweet) {
			if ($this->fromId < $tweet["id"])
				array_unshift($output["new-tweets"], $tweet);
		}
		echo(json_encode($output, true));

	}

	// Cache tweets in JSON file
	private function cacheTweets() {
		$cache = array("tweets" => array_slice($this->tweets, 0, $this->count), "modified" => time());
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
