<?php
/**
 * Featured
 *
 * @author emanuele
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * @version 0.0.1
 */

interface Featurer_Interface
{
	public function getEntryId();

	public function getType();

	public function canAccess();

	public function getContext();
}