<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Order
 *
 * @since  1.5
 */
class RedshopHelperOrder
{
	/**
	 * Order Payment Information
	 *
	 * @var  array
	 */
	protected static $payment = array();

	/**
	 * Order Info
	 *
	 * @var  array
	 */
	protected static $orderInfo = array();

	/**
	 * All the published status code
	 *
	 * @var  null
	 */
	protected static $allStatus = null;

	/**
	 * Get order information from order id.
	 *
	 * @param   integer   $orderId  Order Id
	 * @param   boolean   $force    Force to get order information from DB instead of cache.
	 *
	 * @return  object    Order Information Object
	 */
	public static function getOrderDetail($orderId, $force = false)
	{
		if (array_key_exists($orderId, self::$orderInfo) && !$force)
		{
			return self::$orderInfo[$orderId];
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__redshop_orders'))
					->where($db->qn('order_id') . ' = ' . (int) $orderId);

		// Set the query and load the result.
		self::$orderInfo[$orderId] = $db->setQuery($query, 0, 1)->loadObject();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return null;
		}

		return self::$orderInfo[$orderId];
	}

	/**
	 * Generate Invoice number in chronological order
	 *
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  object   Invoice number clean and formatted value
	 */
	public static function generateInvoiceNumber($orderId)
	{
		$db    = JFactory::getDbo();

		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('invoice_number, invoice_number_chrono, order_status, order_payment_status, order_total')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_id') . ' = ' . (int) $orderId);

		$db->setQuery($query);

		$orderInfo = $db->loadObject();

		// Don't generate invoice number for free orders if disabled from config
		if ($orderInfo->order_total <= 0 && ! (boolean) INVOICE_NUMBER_FOR_FREE_ORDER)
		{
			return;
		}

		$number          = $orderInfo->invoice_number_chrono;
		$formattedNumber = $orderInfo->invoice_number;

		// Check if number is not set and order status is confirm or number is set and order status is refund.
		if (($number <= 0 && 'C' == $orderInfo->order_status && 'Paid' == $orderInfo->order_payment_status)
			|| ($number > 0 && ('R' == $orderInfo->order_status || 'X' == $orderInfo->order_status)))
		{
			$query = $db->getQuery(true)
				->select('MAX(invoice_number_chrono) as max_invoice_number')
				->from($db->qn('#__redshop_orders'));

			// Set the query and load the result.
			$db->setQuery($query);

			$maxInvoiceNo   = $db->loadResult();

			$firstInvoiceNo = (int) FIRST_INVOICE_NUMBER;

			// It will apply only for the first number ideally!
			if ($maxInvoiceNo <= $firstInvoiceNo)
			{
				$maxInvoiceNo += $firstInvoiceNo;
			}

			$number = $maxInvoiceNo + 1;

			self::updateInvoiceNumber($number, $orderId);

			$formattedNumber = self::formatInvoiceNumber($number);
		}

		$invoiceNo        = new stdClass;
		$invoiceNo->clean = $number;
		$invoiceNo->value = $formattedNumber;

		return $invoiceNo;
	}

	/**
	 * Format the given invoice number
	 *
	 * @param   integer  $invoiceNo  Order Invoice Number
	 *
	 * @return  string   Formatted invoice number
	 */
	public static function formatInvoiceNumber($invoiceNo)
	{
		if ($invoiceNo == 0)
		{
			return '';
		}

		if (!REAL_INVOICE_NUMBER_TEMPLATE)
		{
			return $invoiceNo;
		}

		return self::parseNumberTemplate(
			REAL_INVOICE_NUMBER_TEMPLATE,
			$invoiceNo
		);
	}

	/**
	 * Parse Invoice or Order Number template.
	 *
	 * @param   string  $template  Number Template
	 * @param   float   $number    Source number to be replaced
	 *
	 * @return  string  Formatted Invoice Number
	 */
	public static function parseNumberTemplate($template, $number)
	{
		$format = sprintf("%06d", $number);
		$formattedInvoiceNo = str_replace("XXXXXX", $format, $template);
		$formattedInvoiceNo = str_replace("xxxxxx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("######", $format, $formattedInvoiceNo);

		$format = sprintf("%05d", $number);
		$formattedInvoiceNo = str_replace("XXXXX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xxxxx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("#####", $format, $formattedInvoiceNo);

		$format = sprintf("%04d", $number);
		$formattedInvoiceNo = str_replace("XXXX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xxxx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("####", $format, $formattedInvoiceNo);

		$format = sprintf("%03d", $number);
		$formattedInvoiceNo = str_replace("XXX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xxx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("###", $format, $formattedInvoiceNo);

		$format = sprintf("%02d", $number);
		$formattedInvoiceNo = str_replace("XX", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("xx", $format, $formattedInvoiceNo);
		$formattedInvoiceNo = str_replace("##", $format, $formattedInvoiceNo);

		return $formattedInvoiceNo;
	}

	/**
	 * Update invoice number in database
	 *
	 * @param   integer  $number   Order Invoice Number
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  void
	 */
	public static function updateInvoiceNumber($number, $orderId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
				->update($db->qn('#__redshop_orders'))
				->set($db->qn('invoice_number_chrono') . ' = ' . (int) $number)
				->set($db->qn('invoice_number') . ' = ' . $db->q(self::formatInvoiceNumber($number)))
				->where($db->qn('order_id') . ' = ' . (int) $orderId);

		$db->setQuery($query);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}
	}

	/**
	 * Get all the order status code information list
	 *
	 * @return  array  Order Status info
	 */
	public static function getOrderStatusList()
	{
		if (!empty(self::$allStatus))
		{
			return self::$allStatus;
		}

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select(
						array(
							$db->qn('order_status_code', 'value'),
							$db->qn('order_status_name', 'text')
						)
					)
					->from($db->qn('#__redshop_order_status'))
					->where($db->qn('published') . ' = ' . $db->q('1'));

		// Set the query and load the result.
		$db->setQuery($query);
		self::$allStatus = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return null;
		}

		return self::$allStatus;
	}

	/**
	 * Get Order Payment Information
	 *
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  object   Payment Information for orders
	 */
	public static function getPaymentInfo($orderId)
	{
		if (!in_array($orderId, self::$payment))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
						->select('*')
						->from($db->qn('#__redshop_order_payment'))
						->where($db->qn('order_id') . ' = ' . (int) $orderId);

			// Set the query and load the result.
			$db->setQuery($query, 0, 1);
			self::$payment[$orderId] = $db->loadObject();

			// Check for a database error.
			if ($db->getErrorNum())
			{
				JError::raiseWarning(500, $db->getErrorMsg());

				return null;
			}

			// Get plugin information
			$plugin = JPluginHelper::getPlugin(
						'redshop_payment',
						self::$payment[$orderId]->payment_method_class
					);
			$plugin->params = new JRegistry($plugin->params);

			// Set plugin information
			self::$payment[$orderId]->plugin = $plugin;
		}

		return self::$payment[$orderId];
	}

	/**
	 * Get Order shipping user information
	 *
	 * @param   integer  $order_id  Order Id
	 *
	 * @return  object   Order Shipping information object
	 */
	public function getOrderShippingUserInfo($order_id)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__redshop_order_users_info'))
					->where($db->qn('address_type') . ' LIKE ' . $db->q('ST'))
					->where($db->qn('order_id') . ' = ' . (int) $order_id);

		$db->setQuery($query);

		$orderShippingInfo = $db->loadObject();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return null;
		}

		// Add extra field data in order shipping info object
		$orderShippingInfo->fields = $this->getOrderShippingExtraFieldsData($orderShippingInfo->users_info_id);

		return $orderShippingInfo;
	}

	/**
	 * Get order shipping extra field information in array
	 *
	 * @param   integer  $orderUserInfoId  Order Info id
	 *
	 * @return  array    Extra Field name as a key of an array
	 */
	public function getOrderShippingExtraFieldsData($orderUserInfoId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select($db->qn('f.field_name') . ',' . $db->qn('fd.data_txt'))
					->from($db->qn('#__redshop_fields_data', 'fd'))
					->where(
						'(' . $db->qn('fd.section') . ' = 14 OR ' . $db->qn('section') . ' = 15)'
					)
					->where($db->qn('fd.itemid') . ' = ' . (int) $orderUserInfoId);

		$query->leftJoin(
			$db->qn('#__redshop_fields', 'f')
			. ' ON ' . $db->qn('f.field_id') . '=' . $db->qn('fd.fieldid')
		);

		// Set the query and load the result.
		$db->setQuery($query);
		$fields = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return null;
		}

		$fieldsData = array();

		if (!empty($fields))
		{
			foreach ($fields as $field)
			{
				$fieldsData[$field->field_name] = $field->data_txt;
			}
		}

		return $fieldsData;
	}
}
