<?php
/*
+--------------------------------------------------------------------+
| CiviCRM version 3.3                                                |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2010                                |
+--------------------------------------------------------------------+
| This file is a part of CiviCRM.                                    |
|                                                                    |
| CiviCRM is free software; you can copy, modify, and distribute it  |
| under the terms of the GNU Affero General Public License           |
| Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
|                                                                    |
| CiviCRM is distributed in the hope that it will be useful, but     |
| WITHOUT ANY WARRANTY; without even the implied warranty of         |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
| See the GNU Affero General Public License for more details.        |
|                                                                    |
| You should have received a copy of the GNU Affero General Public   |
| License and the CiviCRM Licensing Exception along                  |
| with this program; if not, contact CiviCRM LLC                     |
| at info[AT]civicrm[DOT]org. If you have questions about the        |
| GNU Affero General Public License or the licensing of CiviCRM,     |
| see the CiviCRM license FAQ at http://civicrm.org/licensing        |
+--------------------------------------------------------------------+
*/
/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2010
 * $Id$
 *
 */
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
class CRM_Contribute_DAO_Contribution extends CRM_Core_DAO
{
  /**
   * static instance to hold the table name
   *
   * @var string
   * @static
   */
  static $_tableName = 'civicrm_contribution';
  /**
   * static instance to hold the field values
   *
   * @var array
   * @static
   */
  static $_fields = null;
  /**
   * static instance to hold the FK relationships
   *
   * @var string
   * @static
   */
  static $_links = null;
  /**
   * static instance to hold the values that can
   * be imported / apu
   *
   * @var array
   * @static
   */
  static $_import = null;
  /**
   * static instance to hold the values that can
   * be exported / apu
   *
   * @var array
   * @static
   */
  static $_export = null;
  /**
   * static value to see if we should log any modifications to
   * this table in the civicrm_log table
   *
   * @var boolean
   * @static
   */
  static $_log = true;
  /**
   * Contribution ID
   *
   * @var int unsigned
   */
  public $id;
  /**
   * FK to Contact ID
   *
   * @var int unsigned
   */
  public $contact_id;
  /**
   * FK to Contribution Type
   *
   * @var int unsigned
   */
  public $contribution_type_id;
  /**
   * The Contribution Page which triggered this contribution
   *
   * @var int unsigned
   */
  public $contribution_page_id;
  /**
   * FK to Payment Processor
   *
   * @var int unsigned
   */
  public $payment_processor_id;
  /**
   * FK to Payment Instrument
   *
   * @var int unsigned
   */
  public $payment_instrument_id;
  /**
   * when was contribution submitted
   *
   * @var datetime
   */
  public $created_date;
  /**
   * when was gift received
   *
   * @var datetime
   */
  public $receive_date;
  /**
   * Portion of total amount which is NOT tax deductible. Equal to total_amount for non-deductible contribution types.
   *
   * @var float
   */
  public $non_deductible_amount;
  /**
   * Total amount of this contribution. Use market value for non-monetary gifts.
   *
   * @var float
   */
  public $total_amount;
  /**
   * actual processor fee if known - may be 0.
   *
   * @var float
   */
  public $fee_amount;
  /**
   * actual funds transfer amount. total less fees. if processor does not report actual fee during transaction, this is set to total_amount.
   *
   * @var float
   */
  public $net_amount;
  /**
   * unique transaction id. may be processor id, bank id + trans id, or account number + check number... depending on payment_method
   *
   * @var string
   */
  public $trxn_id;
  /**
   * unique invoice id, system generated or passed in
   *
   * @var string
   */
  public $invoice_id;
  /**
   * 3 character string, value from config setting or input via user.
   *
   * @var string
   */
  public $currency;
  /**
   * when was gift cancelled
   *
   * @var datetime
   */
  public $cancel_date;
  /**
   *
   * @var text
   */
  public $cancel_reason;
  /**
   * when (if) receipt was sent. populated automatically for online donations w/ automatic receipting
   *
   * @var datetime
   */
  public $receipt_date;
  /**
   * when (if) was donor thanked
   *
   * @var datetime
   */
  public $thankyou_date;
  /**
   * Origin of this Contribution.
   *
   * @var string
   */
  public $source;
  /**
   *
   * @var text
   */
  public $amount_level;
  /**
   * Conditional foreign key to civicrm_contribution_recur id. Each contribution made in connection with a recurring contribution carries a foreign key to the recurring contribution record. This assumes we can track these processor initiated events.
   *
   * @var int unsigned
   */
  public $contribution_recur_id;
  /**
   * FK to contact ID
   *
   * @var int unsigned
   */
  public $honor_contact_id;
  /**
   *
   * @var boolean
   */
  public $is_test;
  /**
   *
   * @var boolean
   */
  public $is_pay_later;
  /**
   *
   * @var int unsigned
   */
  public $contribution_status_id;
  /**
   * Implicit FK to civicrm_option_value.
   *
   * @var int unsigned
   */
  public $honor_type_id;
  /**
   * Conditional foreign key to civicrm_address.id. We insert an address record for each contribution when we have associated billing name and address data.
   *
   * @var int unsigned
   */
  public $address_id;
  /**
   *
   * @var string
   */
  public $check_number;
  /**
   *
   * @var string
   */
  public $receipt_id;
  /**
   * when is payment expiration
   *
   * @var datetime
   */
  public $expire_date;
  /**
   * class constructor
   *
   * @access public
   * @return civicrm_contribution
   */
  function __construct()
  {
    parent::__construct();
  }
  /**
   * return foreign links
   *
   * @access public
   * @return array
   */
  function &links()
  {
    if (!(self::$_links)) {
      self::$_links = array(
        'contact_id' => 'civicrm_contact:id',
        'contribution_type_id' => 'civicrm_contribution_type:id',
        'contribution_page_id' => 'civicrm_contribution_page:id',
        'payment_processor_id' => 'civicrm_payment_processor:id',
        'contribution_recur_id' => 'civicrm_contribution_recur:id',
        'honor_contact_id' => 'civicrm_contact:id',
        'address_id' => 'civicrm_address:id',
      );
    }
    return self::$_links;
  }
  /**
   * returns all the column names of this table
   *
   * @access public
   * @return array
   */
  function &fields()
  {
    if (!(self::$_fields)) {
      self::$_fields = array(
        'contribution_id' => array(
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Contribution ID') ,
          'required' => true,
          'import' => true,
          'where' => 'civicrm_contribution.id',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => true,
        ) ,
        'contribution_contact_id' => array(
          'name' => 'contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Contact ID') ,
          'required' => true,
          'import' => true,
          'where' => 'civicrm_contribution.contact_id',
          'headerPattern' => '/contact(.?id)?/i',
          'dataPattern' => '/^\d+$/',
          'export' => true,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
        ) ,
        'contribution_type_id' => array(
          'name' => 'contribution_type_id',
          'type' => CRM_Utils_Type::T_INT,
          'export' => false,
          'where' => 'civicrm_contribution.contribution_type_id',
          'headerPattern' => '',
          'dataPattern' => '',
          'FKClassName' => 'CRM_Contribute_DAO_ContributionType',
        ) ,
        'contribution_page_id' => array(
          'name' => 'contribution_page_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Contribution Page ID') ,
          'import' => true,
          'where' => 'civicrm_contribution.contribution_page_id',
          'headerPattern' => '',
          'dataPattern' => '/^\d+$/',
          'export' => true,
          'FKClassName' => 'CRM_Contribute_DAO_ContributionPage',
        ) ,
        'payment_processor_id' => array(
          'name' => 'payment_processor_id',
          'type' => CRM_Utils_Type::T_INT,
          'FKClassName' => 'CRM_Core_DAO_PaymentProcessor',
        ) ,
        'payment_instrument_id' => array(
          'name' => 'payment_instrument_id',
          'type' => CRM_Utils_Type::T_INT,
        ) ,
        'created_date' => array(
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('Created Date') ,
          'import' => true,
          'where' => 'civicrm_contribution.created_date',
          'headerPattern' => '/created(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => true,
        ) ,
        'receive_date' => array(
          'name' => 'receive_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('Receive Date') ,
          'import' => true,
          'where' => 'civicrm_contribution.receive_date',
          'headerPattern' => '/receive(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => true,
        ) ,
        'non_deductible_amount' => array(
          'name' => 'non_deductible_amount',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => ts('Non-deductible Amount') ,
          'import' => true,
          'where' => 'civicrm_contribution.non_deductible_amount',
          'headerPattern' => '/non?.?deduct/i',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => true,
        ) ,
        'total_amount' => array(
          'name' => 'total_amount',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => ts('Total Amount') ,
          'required' => true,
          'import' => true,
          'where' => 'civicrm_contribution.total_amount',
          'headerPattern' => '/^total|(.?^am(ou)?nt)/i',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => true,
        ) ,
        'fee_amount' => array(
          'name' => 'fee_amount',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => ts('Fee Amount') ,
          'import' => true,
          'where' => 'civicrm_contribution.fee_amount',
          'headerPattern' => '/fee(.?am(ou)?nt)?/i',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => true,
        ) ,
        'net_amount' => array(
          'name' => 'net_amount',
          'type' => CRM_Utils_Type::T_MONEY,
          'title' => ts('Net Amount') ,
          'import' => true,
          'where' => 'civicrm_contribution.net_amount',
          'headerPattern' => '/net(.?am(ou)?nt)?/i',
          'dataPattern' => '/^\d+(\.\d{2})?$/',
          'export' => true,
        ) ,
        'trxn_id' => array(
          'name' => 'trxn_id',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Transaction ID') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => true,
          'where' => 'civicrm_contribution.trxn_id',
          'headerPattern' => '/tr(ansactio|x)n(.?id)?/i',
          'dataPattern' => '',
          'export' => true,
        ) ,
        'invoice_id' => array(
          'name' => 'invoice_id',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Invoice ID') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => true,
          'where' => 'civicrm_contribution.invoice_id',
          'headerPattern' => '/invoice(.?id)?/i',
          'dataPattern' => '',
          'export' => true,
        ) ,
        'currency' => array(
          'name' => 'currency',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Currency') ,
          'maxlength' => 3,
          'size' => CRM_Utils_Type::FOUR,
          'import' => true,
          'where' => 'civicrm_contribution.currency',
          'headerPattern' => '/cur(rency)?/i',
          'dataPattern' => '/^[A-Z]{3}$/i',
          'export' => true,
          'default' => 'UL',
        ) ,
        'cancel_date' => array(
          'name' => 'cancel_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('Cancel Date') ,
          'import' => true,
          'where' => 'civicrm_contribution.cancel_date',
          'headerPattern' => '/cancel(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => true,
        ) ,
        'cancel_reason' => array(
          'name' => 'cancel_reason',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => ts('Cancel Reason') ,
          'import' => true,
          'where' => 'civicrm_contribution.cancel_reason',
          'headerPattern' => '/(cancel.?)?reason/i',
          'dataPattern' => '',
          'export' => true,
        ) ,
        'receipt_date' => array(
          'name' => 'receipt_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('Receipt Date') ,
          'import' => true,
          'where' => 'civicrm_contribution.receipt_date',
          'headerPattern' => '/receipt(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => true,
        ) ,
        'thankyou_date' => array(
          'name' => 'thankyou_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('Thank-you Date') ,
          'import' => true,
          'where' => 'civicrm_contribution.thankyou_date',
          'headerPattern' => '/thank(s|(.?you))?(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => true,
        ) ,
        'contribution_source' => array(
          'name' => 'source',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Contribution Source') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => true,
          'where' => 'civicrm_contribution.source',
          'headerPattern' => '/source/i',
          'dataPattern' => '',
          'export' => true,
        ) ,
        'amount_level' => array(
          'name' => 'amount_level',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => ts('Amount Label') ,
          'import' => true,
          'where' => 'civicrm_contribution.amount_level',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => true,
        ) ,
        'contribution_recur_id' => array(
          'name' => 'contribution_recur_id',
          'type' => CRM_Utils_Type::T_INT,
          'FKClassName' => 'CRM_Contribute_DAO_ContributionRecur',
        ) ,
        'honor_contact_id' => array(
          'name' => 'honor_contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
        ) ,
        'is_test' => array(
          'name' => 'is_test',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Test') ,
          'import' => true,
          'where' => 'civicrm_contribution.is_test',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => true,
        ) ,
        'is_pay_later' => array(
          'name' => 'is_pay_later',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Is Pay Later') ,
          'import' => true,
          'where' => 'civicrm_contribution.is_pay_later',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => true,
        ) ,
        'contribution_status_id' => array(
          'name' => 'contribution_status_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Contribution Status Id') ,
          'import' => true,
          'where' => 'civicrm_contribution.contribution_status_id',
          'headerPattern' => '/status/i',
          'dataPattern' => '',
          'export' => false,
          'default' => '',
        ) ,
        'honor_type_id' => array(
          'name' => 'honor_type_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Honor Type') ,
        ) ,
        'address_id' => array(
          'name' => 'address_id',
          'type' => CRM_Utils_Type::T_INT,
          'FKClassName' => 'CRM_Core_DAO_Address',
        ) ,
        'check_number' => array(
          'name' => 'check_number',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Check Number') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => true,
          'where' => 'civicrm_contribution.check_number',
          'headerPattern' => '/check(.?number)?/i',
          'dataPattern' => '',
          'export' => true,
        ) ,
        'receipt_id' => array(
          'name' => 'receipt_id',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Receipt ID') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'import' => true,
          'where' => 'civicrm_contribution.receipt_id',
          'headerPattern' => '/receipt(.?number)?/i',
          'dataPattern' => '',
          'export' => true,
        ) ,
        'expire_date' => array(
          'name' => 'expire_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('Expire Date') ,
          'import' => true,
          'where' => 'civicrm_contribution.expire_date',
          'headerPattern' => '/expire(.?date)?/i',
          'dataPattern' => '/^\d{4}-?\d{2}-?\d{2} ?(\d{2}:?\d{2}:?(\d{2})?)?$/',
          'export' => true,
        ) ,
      );
    }
    return self::$_fields;
  }
  /**
   * returns the names of this table
   *
   * @access public
   * @return string
   */
  function getTableName()
  {
    return self::$_tableName;
  }
  /**
   * returns if this table needs to be logged
   *
   * @access public
   * @return boolean
   */
  function getLog()
  {
    return self::$_log;
  }
  /**
   * returns the list of fields that can be imported
   *
   * @access public
   * return array
   */
  function &import($prefix = false)
  {
    if (!(self::$_import)) {
      self::$_import = array();
      $fields = & self::fields();
      foreach($fields as $name => $field) {
        if (CRM_Utils_Array::value('import', $field)) {
          if ($prefix) {
            self::$_import['contribution'] = & $fields[$name];
          } else {
            self::$_import[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_import;
  }
  /**
   * returns the list of fields that can be exported
   *
   * @access public
   * return array
   */
  function &export($prefix = false)
  {
    if (!(self::$_export)) {
      self::$_export = array();
      $fields = & self::fields();
      foreach($fields as $name => $field) {
        if (CRM_Utils_Array::value('export', $field)) {
          if ($prefix) {
            self::$_export['contribution'] = & $fields[$name];
          } else {
            self::$_export[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_export;
  }
}
