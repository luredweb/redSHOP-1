<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * The Questions view
 *
 * @package     RedSHOP.Backend
 * @subpackage  Questions.View
 * @since       2.0.0.5
 */
class RedshopViewQuestions extends RedshopViewAdmin
{
	/**
	 * Display the States view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		// Get data from the model
		$model = $this->getModel();

		$this->items			= $this->get('Items');
		$this->pagination		= $this->get('Pagination');
		$this->state			= $this->get('State');
		$this->ordering			= $this->state->get('list.ordering', 'q.ordering');
		$this->activeFilters 	= $model->getActiveFilters();
		$this->filterForm    	= $model->getForm();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode('<br />', $errors));

			return false;
		}

		// Set the tool-bar and number of found items
		$this->addToolBar();

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   2.0.0.4
	 */
	protected function addToolBar()
	{
		$title = JText::_('COM_REDSHOP_QUESTION_MANAGEMENT');

		if ($this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}

		JToolBarHelper::title($title, 'redshop_question_48');
		JToolBarHelper::addNew('question.add');
		JToolBarHelper::editList('question.edit');
		JToolBarHelper::deleteList('', 'questions.delete');
		JToolbarHelper::publish('questions.publish', 'JTOOLBAR_PUBLISH', true);
		JToolbarHelper::unpublish('questions.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	}
}
