<?php
/**
 * Featured
 *
 * @author emanuele
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 0.0.1
 */

class Featured_Integrate
{
	public static function display_buttons()
	{
		global $context, $scripturl;

		if (allowedTo('make_featured'))
		{
			loadLanguage('Featured');
			$context['normal_buttons']['featured'] = array('text' => 'make_featured', 'lang' => true, 'url' => $scripturl . '?action=featured;sa=topic;topic=' . $context['current_topic'] . '.' . $context['start'] . ';last_msg=' . $context['topic_last_message'], 'active' => false);
		}
	}

	public static function boardindex_after()
	{
		global $modSettings, $context;

		$modSettings['featured_amount'] = 5;

		require_once(SUBSDIR . '/widgets/FeaturedEntries.widget.php');
		$widget = new Featured_Entries(database());
		$context['featured_widget'] = $widget->load($modSettings['featured_amount']);
	}
}