<?php

require_once 'fieldvaluepermission.civix.php';

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function fieldvaluepermission_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_ACL_Form_ACL') {
    // if delete then this added code is not necessary
    if ($form->_action & CRM_Core_Action::DELETE) {
      return;
    }
    // Add Option for Contact with Custom Field Value
    $objectTypes =& $form->getElement('object_type');
    $elements =& $objectTypes->getElements();
    $elements[] = $form->createElement('radio', NULL, NULL, 'Contact with Custom Field Value', 100);
    //TODO add/hide fields pick custom field and value see ex: https://github.com/civicrm/civicrm-core/blob/master/CRM/ACL/Form/ACL.php#L47
    $form->addEntityRef('custom_field_id', ts('Custom Field'), array(
      'entity' => 'CustomField',
      'placeholder' => ts('- Select Custom Field -'),
      'select' => array('minimumInputLength' => 0),
      'api' => array(
        'params' => array('custom_group_id.extends' => array('IN' => array("Individual", "Organization", "Contact"))),
        'label_field' => 'label',
      ),
    ));
    $form->add('text', 'custom_field_value', ts('Value to match'));
    $resources = CRM_Core_Resources::singleton();
    CRM_Core_Region::instance('form-body')->add(array(
      'template' => $resources->getPath('org.ndi.fieldvaluepermission', 'templates/customFieldId.tpl'),
    ));
    $resources->addScriptFile('org.ndi.fieldvaluepermission', 'js/aclform.js');
    // Updating so need to set defaults on the form for reference add is 1
    if ($form->_action == 2 && !empty($form->_defaultValues['group_id'])) {
      try {
        $result = civicrm_api3('Group', 'getsingle', array(
          'id' => $form->_defaultValues['object_id'],
          'api.SavedSearch.getsingle' => array('id' => "\$value.saved_search_id"),
        ));
      }
      catch (CiviCRM_API3_Exception $e) {
        $error = $e->getMessage();
        CRM_Core_Error::debug_log_message(t('API Error: %1', array(1 => $error, 'domain' => 'org.ndi.fieldvaluepermission')));
      }
      if ($result['is_hidden']) {
        $defaults = array();
        $defaults['object_type'] = 100;
        if (!empty(array_keys($result['api.SavedSearch.getsingle']['form_values'])[0])) {
          $customField = array_keys($result['api.SavedSearch.getsingle']['form_values'])[0];
          $defaults['custom_field_id'] = substr($customField, 7);
        }
        // TODO make sure works with text and select2
        if (!empty($result['api.SavedSearch.getsingle']['form_values'][$customField]['IN'][0])) {
          $defaults['custom_field_value'] = $result['api.SavedSearch.getsingle']['form_values'][$customField]['IN'][0];
        }
        $form->setDefaults($defaults);
      }
    }
  }
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postProcess
 */
function fieldvaluepermission_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_ACL_Form_ACL' && $form->_submitValues['object_type'] == 100) {
    $formValues = array(
      'custom_' . $form->_submitValues['custom_field_id'] => array(
        'IN' => array($form->_submitValues['custom_field_value']),
      ),
    );
    $hiddenSmartParams = array(
      'group_type' => array('2' => 1),
      'form_values' => $formValues,
      'saved_search_id' => NULL,
      'search_custom_id' => NULL,
      'search_context' => 'advanced',
    );
    list($smartGroupId, $savedSearchId) = CRM_Contact_BAO_Group::createHiddenSmartGroup($hiddenSmartParams);
    $params = $form->controller->exportValues($form->getVar('_name'));
    $params['is_active'] = CRM_Utils_Array::value('is_active', $params, FALSE);
    $params['deny'] = 0;
    $params['entity_table'] = 'civicrm_acl_role';
    $params['object_table'] = 'civicrm_saved_search';
    $params['object_id'] = $smartGroupId;
    if ($form->getVar('_id')) {
      $params['id'] = $form->getVar('_id');
    }
    CRM_ACL_BAO_ACL::create($params);
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function fieldvaluepermission_civicrm_config(&$config) {
  _fieldvaluepermission_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function fieldvaluepermission_civicrm_xmlMenu(&$files) {
  _fieldvaluepermission_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function fieldvaluepermission_civicrm_install() {
  _fieldvaluepermission_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function fieldvaluepermission_civicrm_postInstall() {
  _fieldvaluepermission_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function fieldvaluepermission_civicrm_uninstall() {
  _fieldvaluepermission_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function fieldvaluepermission_civicrm_enable() {
  _fieldvaluepermission_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function fieldvaluepermission_civicrm_disable() {
  _fieldvaluepermission_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function fieldvaluepermission_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _fieldvaluepermission_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function fieldvaluepermission_civicrm_managed(&$entities) {
  _fieldvaluepermission_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function fieldvaluepermission_civicrm_caseTypes(&$caseTypes) {
  _fieldvaluepermission_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function fieldvaluepermission_civicrm_angularModules(&$angularModules) {
  _fieldvaluepermission_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function fieldvaluepermission_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _fieldvaluepermission_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function fieldvaluepermission_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function fieldvaluepermission_civicrm_navigationMenu(&$menu) {
  _fieldvaluepermission_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'org.ndi.fieldvaluepermission')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _fieldvaluepermission_civix_navigationMenu($menu);
} // */
