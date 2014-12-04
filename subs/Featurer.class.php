<?php
/**
 * Featured
 *
 * @author emanuele
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 0.0.1
 */

class Featurer
{
	protected $_item = 0;
	protected $_db = null;
	protected $_access = false;

	public function __construct($item, $db)
	{
		$this->_item = $item;
		$this->_db = $db;

		$this->_access = $this->_item->canAccess();
	}

	public function toggle()
	{
		global $user_info;

		if (!$this->_access)
			return;

		$current_state = $this->_getState();
		$new_state = $this->_doToggle($current_state);
		$this->_logToggle($new_state, $current_state, $user_info['id']);
	}

	protected function _logToggle($new_state, $old_state, $user)
	{
		$this->_db->insert('',
			'{db_prefix}log_featured',
			array(
				'id_entry' => 'int',
				'type' => 'string-10',
				'new_state' => 'int',
				'old_state' => 'int',
				'id_member' => 'int',
				'time' => 'int',
			),
			array(
				$this->_item->getEntryId(),
				$this->_item->getType(),
				$new_state,
				$old_state,
				$user,
				time()
			),
			array('id_entry', 'type')
		);
	}

	protected function _doToggle($old_state)
	{
		// If disabled:
		if ($old_state == 0)
		{
			// insert new
			$this->_feature();
			return 1;
		}
		else
		{
			// delete old
			$this->_unfeature();
			return 0;
		}
	}

	protected function _unfeature()
	{
		$this->_db->query('', '
			DELETE
			FROM {db_prefix}featured
			WHERE id_entry = {int:entry}
				AND type = {string:type}',
			array(
				'entry' => $this->_item->getEntryId(),
				'type' => $this->_item->getType(),
			)
		);
	}

	protected function _feature()
	{
		$this->_db->insert('',
			'{db_prefix}featured',
			array(
				'id_entry' => 'int',
				'type' => 'string-10',
			),
			array(
				$this->_item->getEntryId(),
				$this->_item->getType(),
			),
			array('id_entry', 'type')
		);
	}

	protected function _getState()
	{
		$request = $this->_db->query('', '
			SELECT id_entry
			FROM {db_prefix}featured
			WHERE id_entry = {int:entry}
				AND type = {string:type}',
			array(
				'entry' => $this->_item->getEntryId(),
				'type' => $this->_item->getType(),
			)
		);
		$num_rows = $this->_db->num_rows($request);
		$this->_db->free_result($request);

		return (int) ($num_rows != 0);
	}
}