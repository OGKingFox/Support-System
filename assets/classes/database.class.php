<?php
class Database {

	private $con;
	private $host;
	private $username;
	private $password;
	private $database;
	private $queryCount;

	public function getCategories() {
		return array("Bugs &amp; Glitches", "Suggestion", "New Idea", "Request/Help", "General");
	}

	public function __construct($host, $user, $pass, $db) {
		$this->host 	= $host;
		$this->username = $user;
		$this->password = $pass;
		$this->database = $db;
	}

	public function getQueryCount() {
		return $this->queryCount;
	}
	
	public function connect() {
		try {
			$this->con = new PDO('mysql:host='.$this->host.';dbname='.$this->database.';charset=utf8', $this->username, $this->password);
			$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
			$this->con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		} catch (Exception $e) {
			echo 'Unable to connect to database. Please check credentials and try again.';
			exit;
		}
	}

	public function getCon() {
		return $this->con;
	}

	public function prep($stmt) {
		return $this->con->prepare($stmt);
	}

	public function getAllUsers() {
		$stmt = $this->prep("SELECT * FROM fox_users");
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getUser($name, $pass) {
		$stmt = $this->con->prepare("SELECT * FROM fox_users WHERE username=:name AND password=:pass LIMIT 1");
		$stmt->bindParam(":name", $name);
		$stmt->bindParam(":pass", $pass);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function checkName($name) {
		$stmt = $this->con->prepare("SELECT * FROM fox_users WHERE username=:name LIMIT 1");
		$stmt->bindParam(":name", $name);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function setPassword($name, $pass) {
		$stmt = $this->con->prepare("UPDATE fox_users SET password=:pass WHERE username=:name");
		$stmt->bindParam(":name", $name);
		$stmt->bindParam(":pass", $pass);
		$stmt->execute();
		$this->queryCount++;
	}

	public function setRights($id, $rights) {
		$stmt = $this->con->prepare("UPDATE fox_users SET rights=:rights WHERE id=:id");
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":rights", $rights);
		$stmt->execute();
		$this->queryCount++;
	}

	public function getUsersByRights($rights) {
		$stmt = $this->con->prepare("SELECT * FROM fox_users WHERE rights=:rights");
		$stmt->bindParam(":rights", $rights);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getUserByName($name) {
		$stmt = $this->con->prepare("SELECT * FROM fox_users WHERE username=:name LIMIT 1");
		$stmt->bindParam(":name", $name);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getBannedUsers() {
		$stmt = $this->con->prepare("SELECT * FROM fox_users WHERE banned=1");
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function setBanned($id, $status) {
		$stmt = $this->con->prepare("UPDATE fox_users SET banned=:status WHERE id=:id");
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":status", $status);
		$stmt->execute();
		$this->queryCount++;
	}
	
	public function getUserById($id) {
		$stmt = $this->con->prepare("SELECT * FROM fox_users WHERE id=:id LIMIT 1");
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function setAvatarUrl($name, $url) {
		$stmt = $this->con->prepare("UPDATE fox_users SET avatar_url=:avatar WHERE username=:name");
		$stmt->bindParam(":name", $name);
		$stmt->bindParam(":avatar", $url);
		$stmt->execute();
	}
	
	public function setEmailAddress($name, $email) {
		$stmt = $this->con->prepare("UPDATE fox_users SET email=:mail WHERE username=:name");
		$stmt->bindParam(":name", $name);
		$stmt->bindParam(":mail", $email);
		$stmt->execute();
		$this->queryCount++;
	}
	
	public function getAvatarUrl($name) {
		$stmt = $this->con->prepare("SELECT * FROM fox_users WHERE username=:name LIMIT 1");
		$stmt->bindParam(":name", $name);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC)['avatar_url'];
		$this->queryCount++;
		return $result;
	}

	public function getUserCount() {
		$stmt = $this->con->prepare("SELECT * FROM fox_users");
		$stmt->execute();
		$this->queryCount++;
		return count($stmt->fetchAll(PDO::FETCH_ASSOC));
	}

	public function checkEmail($email) {
		$stmt = $this->con->prepare("SELECT * FROM fox_users WHERE email=:email LIMIT 1");
		$stmt->bindParam(":email", $email);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getPostCount($name) {
		$stmt = $this->con->prepare("SELECT * FROM fox_posts WHERE poster=:name");
		$stmt->bindParam(":name", $name);
		$stmt->execute();
		$count1 = count($stmt->fetchAll(PDO::FETCH_ASSOC));
		$this->queryCount++;
		$stmt = $this->con->prepare("SELECT * FROM fox_replies WHERE author=:name2");
		$stmt->bindParam(":name2", $name);
		$stmt->execute();
		$count2 = count($stmt->fetchAll(PDO::FETCH_ASSOC));
		$this->queryCount++;
		return ($count1 + $count2);
	}

	public function registerUser($name, $pass, $email) {
		$stmt = $this->con->prepare("INSERT INTO fox_users (username, password, rights, banned, email) VALUES (:name, :pass, 0, 0, :email)");
		$stmt->bindParam(":name", $name);
		$stmt->bindParam(":pass", $pass);
		$stmt->bindParam(":email", $email);
		$this->queryCount++;
		return $stmt->execute();
	}

	public function countAllTopics($cat) {
		if ($cat == -1) {
			$stmt = $this->con->prepare("SELECT * FROM fox_posts ORDER BY state DESC, date");
		} else {
			$stmt = $this->con->prepare("SELECT * FROM fox_posts WHERE category=:cat ORDER BY state DESC, date DESC");
			$stmt->bindParam(":cat", $cat, PDO::PARAM_INT);
		}
		$stmt->execute();
		$this->queryCount++;
		return count($stmt->fetchAll(PDO::FETCH_ASSOC));
	}

	public function getAllPosts($min, $max, $cat, $sort) {
		if ($cat == -1) {
			$stmt = $this->con->prepare("SELECT * FROM fox_posts ORDER BY state DESC, $sort DESC LIMIT :min, :max");
		} else {
			$stmt = $this->con->prepare("SELECT * FROM fox_posts WHERE category=:cat ORDER BY state DESC, $sort DESC LIMIT :min, :max");
			$stmt->bindParam(":cat", $cat, PDO::PARAM_INT);
		}
		$stmt->bindParam(":min", $min, PDO::PARAM_INT);
		$stmt->bindParam(":max", $max, PDO::PARAM_INT);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function setLastPostTime($tid, $time) {
		$stmt = $this->con->prepare("UPDATE fox_posts SET last_post=:times WHERE id=:tid LIMIT 1");
		$stmt->bindParam(":tid", $tid);
		$stmt->bindParam(":times", $time);
		$stmt->execute();
	}

	public function getTopicReplies($topicId) {
		$stmt = $this->con->prepare("SELECT * FROM fox_replies WHERE topic=:topic");
		$stmt->bindParam(":topic", $topicId);
		$stmt->execute();
		$this->queryCount++;
		return count($stmt->fetchAll(PDO::FETCH_ASSOC));
	}

	public function getState($state) {
		if ($state == 0)
			return "In-Progress";
		if ($state == 1)
			return "Open";
		if ($state == 2)
			return "Resolved";
		if ($state == 3)
			return "Closed";
	}

	public function isValidCategory($type) {
		return is_numeric($type) && $type >= 0 && $type <= count($this->getCategories());
	}

	public function addLike($post, $name) {
		$topic = $this->getPost($post);

		if ($topic['state'] == 2 || $topic['state'] == 3) {
			return;
		}

		$likes = $topic['likes'];
		$likes++;

		$stmt = $this->con->prepare("UPDATE fox_posts SET likes=:amt WHERE id=:postId LIMIT 1");
		$stmt->bindParam(":amt", $likes);
		$stmt->bindParam(":postId", $post);
		$stmt->execute();
		$this->queryCount++;
		$this->recordLike($post, $name, 0);
	}

	public function addDislike($post, $name) {
		$topic = $this->getPost($post);

		if ($topic['state'] == 2 || $topic['state'] == 3) {
			return;
		}

		$likes = $topic['dislikes'];
		$likes++;

		$stmt = $this->con->prepare("UPDATE fox_posts SET dislikes=:amt WHERE id=:postId LIMIT 1");
		$stmt->bindParam(":amt", $likes);
		$stmt->bindParam(":postId", $post);
		$stmt->execute();
		$this->queryCount++;
		$this->recordLike($post, $name, 1);
	}

	public function hasLiked($post, $name) {
		$stmt = $this->con->prepare("SELECT * FROM fox_likes WHERE postId=:post AND name=:name AND type=0 LIMIT 1");
		$stmt->bindParam(":post", $post);
		$stmt->bindParam(":name", $name);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function hasDisliked($post, $name) {
		$stmt = $this->con->prepare("SELECT * FROM fox_likes WHERE postId=:post AND name=:name AND type=1 LIMIT 1");
		$stmt->bindParam(":post", $post);
		$stmt->bindParam(":name", $name);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function recordLike($post, $name, $type) {
		$stmt = $this->con->prepare("INSERT INTO fox_likes (postId, name, type) VALUES (:post, :name, :type)");
		$stmt->bindParam(":post", $post);
		$stmt->bindParam(":name", $name);
		$stmt->bindParam(":type", $type);
		$stmt->execute();
		$this->queryCount++;
	}

	public function getPost($id) {
		$stmt = $this->con->prepare("SELECT * FROM fox_posts WHERE id=:postId LIMIT 1");
		$stmt->bindParam(":postId", $id);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function createPost($poster, $category, $title, $body) {
		$category = intval($category);
		$poster   = htmlentities($poster);
		$title 	  = htmlentities($title);

		$stmt = $this->con->prepare("INSERT INTO fox_posts (category, title, body, poster, last_post, state, likes, dislikes) VALUES (:cat, :title, :body, :author, ".time().", 1, 0, 0)");
		$stmt->bindParam(":cat", $category);
		$stmt->bindParam(":title", $title);
		$stmt->bindParam(":body", $body);
		$stmt->bindParam(":author", $poster);
		$this->queryCount++;
		return $stmt->execute();
	}

	public function updatePost($id, $body) {
		$stmt = $this->con->prepare("UPDATE fox_posts SET body=:body WHERE id=:id");
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":body", $body);
		$this->queryCount++;
		return $stmt->execute();
	}

	public function addReply($topic, $author, $body) {
		$stmt = $this->con->prepare("INSERT INTO fox_replies (author, topic, body) VALUES (:author, :topic, :body)");
		$stmt->bindParam(":author", $author);
		$stmt->bindParam(":topic", $topic);
		$stmt->bindParam(":body", $body);
		$this->queryCount++;

		$this->setLastPostTime($topic, time());

		return $stmt->execute();
	}

	public function getReply($id) {
		$stmt = $this->con->prepare("SELECT * FROM fox_replies WHERE id=:id");
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getAllReplies($id) {
		$stmt = $this->con->prepare("SELECT * FROM fox_replies WHERE topic=:id");
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getReplies($topic, $min, $max) {
		$stmt = $this->con->prepare("SELECT * FROM fox_replies WHERE topic=:tid ORDER BY id ASC LIMIT :min, :max");
		$stmt->bindParam(":tid", $topic);
		$stmt->bindParam(":min", $min, PDO::PARAM_INT);
		$stmt->bindParam(":max", $max, PDO::PARAM_INT);
		$stmt->execute();
		$this->queryCount++;
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function setPostStatus($id, $state) {
		$stmt = $this->con->prepare("UPDATE fox_posts SET state=:state WHERE id=:postId LIMIT 1");
		$stmt->bindParam(":state", $state);
		$stmt->bindParam(":postId", $id);
		$this->queryCount++;
		return $stmt->execute();
	}

	public function deleteTopic($topicId) {
		$stmt = $this->con->prepare("DELETE FROM fox_posts WHERE id=:id LIMIT 1");
		$stmt->bindParam(":id", $topicId);
		$this->queryCount++;
		return $stmt->execute();
	}

	public function deleteReply($replyId) {
		$stmt = $this->con->prepare("DELETE FROM fox_replies WHERE id=:id LIMIT 1");
		$stmt->bindParam(":id", $replyId);
		$this->queryCount++;
		return $stmt->execute();
	}

	public function deleteTopicAndReplies($topicId) {
		$stmt = $this->con->prepare("DELETE FROM fox_posts WHERE id=:id LIMIT 1");
		$stmt->bindParam(":id", $topicId);
		$stmt->execute();
		$stmt = $this->con->prepare("DELETE FROM fox_replies WHERE topic=:id");
		$stmt->bindParam(":id", $topicId);
		$stmt->execute();
		$this->queryCount++;
	}

	public function countTopics() {
		$stmt = $this->con->prepare("SELECT * FROM fox_posts");
		$stmt->execute();
		$this->queryCount++;
		return count($stmt->fetchAll(PDO::FETCH_ASSOC));
	}

	public function countReplies() {
		$stmt = $this->con->prepare("SELECT * FROM fox_replies");
		$stmt->execute();
		$this->queryCount++;
		return count($stmt->fetchAll(PDO::FETCH_ASSOC));
	}
	
	public function getAllowedTags() {
		return "<a><b><u><i><h2><h3><h4><ul><li><p><hr><br><pre>";
	}

	public static function convertLinks($s) {
 		return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
	}

	function decrypt($data, $key) {
	    $iv=pack("H*" , substr($data,0,16));
	    $x =pack("H*" , substr($data,16)); 
	    $res = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $x , MCRYPT_MODE_CBC, $iv);
	    return $res;
	}

	function encrypt($data, $key) {
	    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    $crypttext = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $data, MCRYPT_MODE_CBC, $iv);
	    return bin2hex($iv . $crypttext);
	}

}