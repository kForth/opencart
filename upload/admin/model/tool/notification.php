<?php
namespace Opencart\Admin\Model\Tool;
/**
 * Class Notification
 *
 * @package Opencart\Admin\Model\Tool
 */
class Notification extends \Opencart\System\Engine\Model {
	/**
	 * Add Notification
	 *
	 * @param array $data
	 *
	 * @return int
	 */
	public function addNotification(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "notification` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `text` = '" . $this->db->escape((string)$data['text']) . "', `status` = '" . (bool)$data['status'] . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Edit Status
	 *
	 * @param int  $notification_id
	 * @param bool $status
	 *
	 * @return void
	 */
	public function editStatus(int $notification_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "notification` SET `status` = '" . (bool)$status . "' WHERE `notification_id` = '" . (int)$notification_id . "'");
	}

	/**
	 * Delete Notification
	 *
	 * @param int $notification_id
	 *
	 * @return void
	 */
	public function deleteNotification(int $notification_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "notification` WHERE `notification_id` = '" . (int)$notification_id . "'");
	}

	/**
	 * Get Notification
	 *
	 * @param int $notification_id
	 *
	 * @return array
	 */
	public function getNotification(int $notification_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "notification` WHERE `notification_id` = '" . (int)$notification_id . "'");

		return $query->row;
	}

	/**
	 * Get Notifications
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function getNotifications(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notification`";

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " WHERE `status` = '" . (bool)$data['filter_status'] . "'";
		}

		$sql .= " ORDER BY `date_added` DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Notifications
	 *
	 * @param array $data
	 *
	 * @return int
	 */
	public function getTotalNotifications(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "notification`";

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " WHERE `status` = '" . (bool)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
