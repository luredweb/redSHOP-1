<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Tax Rate
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.0.6
 */
class RedshopModelTax_Rate extends RedshopModelForm
{
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   2.0.0.4
	 */
	public function save($data)
	{
		$post = JFactory::getApplication()->input->post->getArray();

		if (!empty($post['jform_state_code']))
		{
			$data['tax_state'] = $post['jform_state_code'];
		}

		return parent::save($data);
	}
}
