<?php
///////////////////////////////////////////////////
// Author: Resul ÇAĞLAYAN
// Project: MVC Framework
///////////////////////////////////////////////////

namespace Session;
final class DB {
	public $expire = '';

	public function __construct($registry) {
		$this->db = $registry->get('db');

		$this->expire = ini_get('session.gc_maxlifetime');
	}

	public function read($session_id) {

		$sess_cont = $this->db->query("SHOW TABLES LIKE 'session'");
		if ($sess_cont->num_rows < 1) {
			$this->db->query("CREATE TABLE `session` (`session_id` varchar(32) NOT NULL, `data` text NOT NULL, `expire` datetime NOT NULL, PRIMARY KEY (`session_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
		}

		$query = $this->db->query("SELECT `data` FROM `session` WHERE session_id = '" . $this->db->escape($session_id) . "' AND expire > " . (int) time());
		if ($query->num_rows) {
			return json_decode($query->row['data'], true);
		} else {
			return false;
		}
	}

	public function write($session_id, $data) {

		if ($session_id) {
			$this->db->query("REPLACE INTO `session` SET session_id = '" . $this->db->escape($session_id) . "', `data` = '" . $this->db->escape(json_encode($data)) . "', expire = '" . $this->db->escape(date('Y-m-d H:i:s', time() + $this->expire)) . "'");
		}
		return true;
	}

	public function destroy($session_id) {
		$this->db->query("DELETE FROM `session` WHERE session_id = '" . $this->db->escape($session_id) . "'");

		return true;
	}

	public function gc($expire) {
		$this->db->query("DELETE FROM `session` WHERE expire < " . ((int) time() + $expire));

		return true;
	}
}
