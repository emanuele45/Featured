<?php
/**
 * Featured
 *
 * @author emanuele
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 0.0.1
 */

class Featured_Entries
{
	protected $_db = null;
	protected $_amount = 10;
	protected $_order = '';

	public function __construct($db)
	{
		$this->_db = $db;
	}

	public function load($amount, $order = 'random')
	{
		$this->_amount = min(10, max(0, $amount));
		$this->_order = in_array($order, array('asc', 'desc', 'random')) ? $order : 'random';

		loadLanguage('Featured');
		loadTemplate('Featured');
		Template_layers::getInstance()->addAfter('featured', 'body');
		LoadJavascriptFile(array('jquery.jcarousel.min.js', 'jcarousel.responsive.js'), array('defer' => true));
		loadCSSFile('jcarousel.responsive.css');

		return $this->_loadContext();
	}

	protected function _loadContext()
	{
		$featured = array();
		require_once(SUBSDIR . '/Featurer/Featurer.interface.php');

		foreach ($this->_getEntries() as $entry)
		{
			require_once(SUBSDIR . '/Featurer/' . ucfirst($entry['type']) . '.featurer.php');
			$class_name = ucfirst($entry['type']) . '_Featurer';
			$featured_entry = new $class_name($entry['id_entry'], $this->_db);
			$item = $featured_entry->getContext();
			$item['body'] = Util::shorten_html($item['body'], 300);

			$featured[] = $item;
		}

		return $featured;
	}

	protected function _getOrder($order)
	{
		switch ($order)
		{
			case 'random':
				return 'RAND()';
			case 'asc':
				return 'id_entry ASC';
			case 'desc':
				return 'id_entry DESC';
		}
	}

	protected function _getEntries()
	{
		$request = $this->_db->query('get_random_number', '
			SELECT id_entry, type
			FROM {db_prefix}featured
			ORDER BY {raw:ordering}
			LIMIT {int:amount}',
			array(
				'ordering' => $this->_getOrder($this->_order),
				'amount' => $this->_amount
			)
		);
		$return = array();
		while ($row = $this->_db->fetch_assoc($request))
			$return[] = $row;
		$this->_db->free_result($request);

		return $return;
	}
}