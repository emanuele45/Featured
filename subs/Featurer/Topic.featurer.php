<?php
/**
 * Featured
 *
 * @author emanuele
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 0.0.1
 */

class Topic_Featurer implements Featurer_Interface
{
	protected $_topic = 0;
	protected $_db = null;

	public function __construct($topic, $db)
	{
		$this->_topic = $topic;
		$this->_db = $db;
	}

	public function getType()
	{
		return 'topic';
	}

	public function getEntryId()
	{
		return $this->_topic;
	}

	public function getContext()
	{
		global $scripturl;

		require_once(SUBSDIR . '/Topic.subs.php');
		$topic_info = getTopicInfo($this->_topic, 'starter');

		$topic_info['subject'] = censorText($topic_info['subject']);
		$topic_info['body'] = censorText($topic_info['body']);
		$topic_info['subject'] = parse_bbc($topic_info['subject']);
		$topic_info['body'] = parse_bbc($topic_info['body']);

		return array(
			'title' => $topic_info['subject'],
			'body' => $topic_info['body'],
			'poster' => array(
				'id' => $topic_info['id_member'],
				'name' => $topic_info['poster_name'],
			),
			'url' => $scripturl . '?topic=' . $topic_info['id_topic'] . '.0',
			'time' => array(
				'standard' => standardTime($topic_info['poster_time']),
				'html' => htmlTime($topic_info['poster_time']),
				'timestamp' => forum_time(true, $topic_info['poster_time']),
			),
		);
	}

	public function canAccess()
	{
		try
		{
			global $modSettings;

			$request = $this->_db->query('', '
				SELECT t.id_topic
				FROM {db_prefix}topics AS t
					INNER JOIN {db_prefix}boards AS b ON (b.id_board = t.id_board)
				WHERE t.id_topic = {int:search_topic_id}
					AND {query_see_board}' . ($modSettings['postmod_active'] ? '
					AND t.approved = {int:is_approved_true}' : '') . '
				LIMIT 1',
				array(
					'is_approved_true' => 1,
					'search_topic_id' => $this->_topic,
				)
			);
			$num_rows = $this->_db->num_rows($request);
			$this->_db->free_result($request);

			return $num_rows != 0;
		}
		catch (Exception $e)
		{
			return false;
		}

		return true;
	}
}