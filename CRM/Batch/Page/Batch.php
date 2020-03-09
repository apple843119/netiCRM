<?php
/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 */

/**
 * Page for displaying list of current batches
 */
class CRM_Batch_Page_Batch extends CRM_Core_Page_Basic {

  /**
   * The action links that we need to display for the browse screen.
   *
   * @var array
   */
  public static $_links = NULL;

  /**
   * Get BAO Name.
   *
   * @return string
   *   Classname of BAO.
   */
  public function getBAOName() {
    return 'CRM_Batch_BAO_Batch';
  }

  /**
   * Get action Links.
   */
  public function &links() {
  }

  /**
   * Get name of edit form.
   *
   * @return string
   *   Classname of edit form.
   */
  public function editForm() {
    return 'CRM_Batch_Form_Batch';
  }

  /**
   * Get edit form name.
   *
   * @return string
   *   name of this page.
   */
  public function editName() {
    return ts('Batch Processing');
  }

  /**
   * Get user context.
   *
   * @param null $mode
   *
   * @return string
   *   user context.
   */
  public function userContext($mode = NULL) {
    return CRM_Utils_System::currentPath();
  }

  /**
   * Browse all entities.
   */
  public function browse() {
    $this->search();
    $label = CRM_Utils_Request::retrieve('label', 'Positive', $this);
    $statusIds = CRM_Utils_Request::retrieve('status_id', 'Positive', $this);
    $typeIds = CRM_Utils_Request::retrieve('type_id', 'Positive', $this);
    $qfKey = CRM_Utils_Request::retrieve('qfKey', 'String', $this);
    $action = array_sum(array_keys($this->links()));

    $batchStatusLabel = CRM_Core_OptionGroup::values('batch_status');
    $batchTypeLabel = CRM_Core_OptionGroup::values('batch_type');

    $dao = new CRM_Batch_DAO_Batch();
    if ($label) {
      $dao->whereAdd("label LIKE '%".CRM_Utils_Type::escape($label, 'String')."%'");
    }
    if ($statusIds && is_array($statusIds)) {
      $dao->whereAdd("status_id IN (".implode(",", $statusIds).")");
    }
    if ($typeIds && is_array($typeIds)) {
      $dao->whereAdd("status_id IN (".implode(",", $typeIds).")");
    }
    $dao->orderBy('created_date ASC');
    $dao->find();

    $rows = array();
    while ($dao->fetch()) {
      $meta = NULL;
      $row = array();
      if ($dao->data) {
        $meta = unserialize($dao->data);
      }
      $contact = CRM_Contact_BAO_Contact::getDisplayAndImage($dao->created_id);
      $row['id'] = $dao->id;
      $row['label'] = $dao->label; 
      $row['created_by'] = $contact[0];
      $row['created_date'] = $dao->created_date;
      $row['modified_date'] = $dao->modified_date;
      $row['batch_status'] = $batchStatusLabel[$dao->status_id];
      $row['batch_type'] = $batchTypeLabel[$dao->type_id];
      if (!empty($meta['total'])) {
        $row['processed'] = $meta['processed'].' / '.$meta['total'];
      }
      $row['action'] = CRM_Core_Action::formLink(self::links(), $action, array(
        'id' => $dao->id, 'qfKey' => $qfKey)
      );
      $rows[$dao->id] = $row;
    }
    $this->assign('rows', $rows);

    // Let template know if user has run a search or not
    /*
    if ($this->get('whereClause')) {
      $this->assign('isSearch', 1);
    }
    else {
      $this->assign('isSearch', 0);
    }
    */
  }

  public function search() {
    if ($this->_action & (CRM_Core_Action::ADD | CRM_Core_Action::UPDATE | CRM_Core_Action::DELETE) ) {
      return;
    }

    $form = new CRM_Core_Controller_Simple('CRM_Batch_Form_Search', ts('Search Batches'), CRM_Core_Action::ADD);
    $form->setEmbedded(TRUE);
    $form->setParent($this);
    $form->process();
    $form->run();
  }

}
