<?php
/*
  Id: pdf_catalog.php

  Copyright (c) 2013 Jason Clark(mithereal@gmail.com)

  Released under the GNU General Public License
  
  For more information, please see the github repo: http://github.com/mithereal
  
  Coded to: Dethklok, Make them Suffer
*/
class ControllerModuleCategorylatestproducts extends Controller {
	private $error = array(); 
		
	public function index() {   
		$this->load->language('module/pdf_catalog');

		$this->document->setTitle = $this->language->get('heading_title');
		$this->load->helper('tcpdf/tcpdf');
		$this->load->model('setting/setting');
		$this->load->model('design/layout');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('pdf_catalog', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
		}
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_hide'] = $this->language->get('text_hide');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_description'] = $this->language->get('entry_description');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/pdf_catalog', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/pdf_catalog', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['modules'] = array();
	
             
		if (isset($this->request->post['pdf_catalog_template_type'])) {
			$this->data['pdf_catalog_template_type'] = $this->request->post['pdf_catalog_template_type'];
		} else {
			$this->data['pdf_catalog_template_type'] = $this->config->get('pdf_catalog_template_type');
		}	
					
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['pdf_catalog_module'])) {
			$this->data['modules'] = $this->request->post['pdf_catalog_module'];
		} elseif ($this->config->get('pdf_catalog_module')) { 
			$this->data['modules'] = $this->config->get('pdf_catalog_module');
		}	
		
		$this->data['token'] = $this->session->data['token'];
		
		
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/pdf_catalog.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
		
                public function uninstall() {
            $this->db->query("
	DELETE FROM " . DB_PREFIX . "setting 
	WHERE `group` = 'pdf_catalog' 
	");
        }
        
	public function install() {
$this->load->model('setting/setting');     
$this->db->query("
	INSERT INTO " . DB_PREFIX . "setting (
	`setting_id` ,
	`store_id` ,
	`group` ,
	`key` ,
	`value` ,
	`serialized`
	)
	VALUES (
	NULL , '0', 'categorylatestproducts', 'pdf_catalog_image_height', '100', '0'
	),(
	NULL , '0', 'categorylatestproducts', 'pdf_catalog_image_width', '100', '0'
	),(
	NULL , '0', 'categorylatestproducts', 'pdf_catalog_display_toc', '1', '0'
	),(
	NULL , '0', 'categorylatestproducts', 'pdf_catalog_item_per_page', '6', '0'
	),(
	NULL , '0', 'categorylatestproducts', 'pdf_catalog_max_products', '200', '0'
	),(
	NULL , '0', 'categorylatestproducts', 'pdf_catalog_description_chars', '75', '0'
	),(
	NULL , '0', 'categorylatestproducts', 'pdf_catalog_template_type', 'html', '0'
	),(
	NULL , '0', 'categorylatestproducts', 'pdf_catalog_display_description', '0', '0'
	);");
        }
        
        
                
                
        private function validate() {
		if (!$this->user->hasPermission('modify', 'module/categorylatestproducts')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>
