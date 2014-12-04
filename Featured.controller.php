<?php
/**
 * Featured
 *
 * @author emanuele
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 0.0.1
 */

class Featured_Controller extends Action_Controller
{
	public function action_index()
	{
		redirectexit();
	}

	public function action_topic()
	{
		global $topic;

		require_once(SUBSDIR . '/Featurer/Featurer.interface.php');
		require_once(SUBSDIR . '/Featurer/Topic.featurer.php');
		$this->_db = database();
		$this->_feature(new Topic_Featurer($topic, $this->_db));

		redirectexit('topic=' . $topic . '.' . $_REQUEST['start']);
	}

	protected function _feature($item)
	{
		if (allowedTo('make_featured'))
		{
			require_once(SUBSDIR . '/Featurer.class.php');

			$featurer = new Featurer($item, $this->_db);
			$featurer->toggle();
		}
	}
}