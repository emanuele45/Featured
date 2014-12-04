<?php
/**
 * Featured
 *
 * @author emanuele
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 0.0.1
 */

function template_featured_above()
{
	global $context, $txt;

	echo '
	<div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>';
	foreach ($context['featured_widget'] as $entry)
		echo '
				<li>
					<h3 class="listlevel1"><a class="linklevel1 active" href="', $entry['url'], '">', $entry['title'], '</a></h3>
					<div>', $entry['body'], '
					</div>
					<span class="readmore"><a class="linkbutton" href="', $entry['url'], '">', $txt['read_more'], '</a></span>
				</li>';
	echo '
			</ul>

			<a href="#" class="jcarousel-control-prev">&lsaquo;</a>
			<a href="#" class="jcarousel-control-next">&rsaquo;</a>

			<p class="jcarousel-pagination"></p>
		</div>
	</div>';
}