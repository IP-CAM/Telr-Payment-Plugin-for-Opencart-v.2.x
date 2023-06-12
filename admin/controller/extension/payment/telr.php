<?php
class ControllerExtensionPaymentTelr extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('extension/payment/telr');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if(!$this->version_ok()) {
			$this->error['warning'] = "This module is not supported on this version of OpenCart. Please upgrade to OpenCart 2.3.0 or later";
		}


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->request->post['telr_defaults']='set';
			$this->model_setting_setting->editSetting('telr', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=payment', true));

		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_test'] = $this->language->get('text_test');

		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_authkey'] = $this->language->get('entry_authkey');
		$data['entry_callback'] = $this->language->get('entry_callback');
		$data['entry_test'] = $this->language->get('entry_test');		
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_purdesc'] = $this->language->get('entry_purdesc');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_pend_status'] = $this->language->get('entry_pend_status');
		$data['entry_comp_status'] = $this->language->get('entry_comp_status');
		$data['entry_void_status'] = $this->language->get('entry_void_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['help_store'] = $this->language->get('help_store');
		$data['help_authkey'] = $this->language->get('help_authkey');
		$data['help_callback'] = $this->language->get('help_callback');
		$data['help_test'] = $this->language->get('help_test');		
		$data['help_total'] = $this->language->get('help_total');
		$data['help_purdesc'] = $this->language->get('help_purdesc');
		$data['help_title'] = $this->language->get('help_title');
		$data['help_geo_zone'] = $this->language->get('help_geo_zone');
		$data['help_status'] = $this->language->get('help_status');
		$data['help_sort_order'] = $this->language->get('help_sort_order');

		$data['entry_lang'] = $this->language->get('entry_lang');		
		$data['lang_en'] = $this->language->get('lang_en');		
		$data['lang_ar'] = $this->language->get('lang_ar');	
		$data['entry_pay_mode'] = $this->language->get('entry_pay_mode');		
		$data['pay_std'] = $this->language->get('pay_std');		
		$data['pay_frm'] = $this->language->get('pay_frm');		



		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['store'])) {
			$data['error_store'] = $this->error['store'];
		} else {
			$data['error_store'] = '';
		}

		if (isset($this->error['authkey'])) {
			$data['error_authkey'] = $this->error['authkey'];
		} else {
			$data['error_authkey'] = '';
		}
		
		if (isset($this->error['purdesc'])) {
			$data['error_purdesc'] = $this->error['purdesc'];
		} else {
			$data['error_purdesc'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'	=> $this->language->get('text_home'),
			'href'	=> $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'	=> $this->language->get('text_payment'),
			'href'	=> $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'	=> $this->language->get('heading_title'),
			'href'	=> $this->url->link('extension/payment/telr', 'token=' . $this->session->data['token'], true),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('extension/payment/telr', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true);

		//$data['callback'] = HTTP_CATALOG . 'index.php?route=payment/telr/callback';

		if (isset($this->request->post['telr_store'])) {
			$data['telr_store'] = $this->request->post['telr_store'];
		} else {
			$data['telr_store'] = $this->config->get('telr_store');
		}

		if (isset($this->request->post['telr_authkey'])) {
			$data['telr_authkey'] = $this->request->post['telr_authkey'];
		} else {
			$data['telr_authkey'] = $this->config->get('telr_authkey');
		}


		if (isset($this->request->post['telr_test'])) {
			$data['telr_test'] = $this->request->post['telr_test'];
		} else {
			$data['telr_test'] = $this->config->get('telr_test');
			if (empty($data['telr_test'])) {
				$data['telr_test']='Yes';
			}
		}

		if (isset($this->request->post['telr_total'])) {
			$data['telr_total'] = $this->request->post['telr_total'];
		} else {
			$data['telr_total'] = $this->config->get('telr_total');
		}

		if (isset($this->request->post['telr_purdesc'])) {
			$data['telr_purdesc'] = $this->request->post['telr_purdesc'];
		} else {
			$data['telr_purdesc'] = $this->config->get('telr_purdesc');
		}

		if (isset($this->request->post['telr_title'])) {
			$data['telr_title'] = $this->request->post['telr_title'];
		} else {
			$data['telr_title'] = $this->config->get('telr_title');
			if (empty($data['telr_title'])) {
				$data['telr_title']='Credit/Debit Card (Telr)';
			}
		}

		if (isset($this->request->post['telr_pend_status_id'])) {
			$data['telr_pend_status_id'] = $this->request->post['telr_pend_status_id'];
		} else {
			$data['telr_pend_status_id'] = $this->config->get('telr_pend_status_id');
			if (empty($data['telr_pend_status_id'])) {
				$data['telr_pend_status_id']='1';
			}
		}
		if (isset($this->request->post['telr_comp_status_id'])) {
			$data['telr_comp_status_id'] = $this->request->post['telr_comp_status_id'];
		} else {
			$data['telr_comp_status_id'] = $this->config->get('telr_comp_status_id');
			if (empty($data['telr_comp_status_id'])) {
				$data['telr_comp_status_id']='2';
			}
		}
		if (isset($this->request->post['telr_void_status_id'])) {
			$data['telr_void_status_id'] = $this->request->post['telr_void_status_id'];
		} else {
			$data['telr_void_status_id'] = $this->config->get('telr_void_status_id');
			if (empty($data['telr_void_status_id'])) {
				$data['telr_void_status_id']='7';
			}
		}

		if (isset($this->request->post['telr_lang'])) {
			$data['telr_lang'] = $this->request->post['telr_lang'];
		} else {
			$data['telr_lang'] = $this->config->get('telr_lang');
			if (empty($data['telr_lang'])) {
				$data['telr_lang']='en';
			}
		}

		if (isset($this->request->post['telr_pay_mode'])) {
			$data['telr_pay_mode'] = $this->request->post['telr_pay_mode'];
		} else {
			$data['telr_pay_mode'] = $this->config->get('telr_pay_mode');
			if (empty($data['telr_pay_mode'])) {
				$data['telr_pay_mode']='0';
			}
		}

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['telr_geo_zone_id'])) {
			$data['telr_geo_zone_id'] = $this->request->post['telr_geo_zone_id'];
		} else {
			$data['telr_geo_zone_id'] = $this->config->get('telr_geo_zone_id');
		}
		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['telr_status'])) {
			$data['telr_status'] = $this->request->post['telr_status'];
		} else {
			$data['telr_status'] = $this->config->get('telr_status');
		}

		if (isset($this->request->post['telr_sort_order'])) {
			$data['telr_sort_order'] = $this->request->post['telr_sort_order'];
		} else {
			$data['telr_sort_order'] = $this->config->get('telr_sort_order');
		}
		$defaults=$this->config->get('telr_defaults');
		if (empty($defaults)) {
			$data['telr_title'] = 'Credit/Debit Card (Telr)';	// Module Title
			$data['telr_test'] = 'Yes';				// Test mode
			$data['telr_pend_status_id'] = '1';			// Pending
			$data['telr_comp_status_id'] = '2';			// Processing
			$data['telr_void_status_id'] = '7';			// Cancelled
			$data['telr_status'] = '1';				// Enabled			
			$data['telr_lang'] = 'en';				// Enabled			
			$data['telr_pay_mode'] = '0';				// Enabled			
		}
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/telr.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/telr')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$store = $this->request->post['telr_store'];
		$store = intval(preg_replace('/[^0-9]+/', '', $store), 10);
		$this->request->post['telr_store'] = (string)$store;

		if ($store<=0) {
			$this->error['store'] = $this->language->get('error_store');
		}

		if (!$this->request->post['telr_authkey']) {
			$this->error['authkey'] = $this->language->get('error_authkey');
		}
		
		if (!$this->request->post['telr_purdesc'] || strlen($this->request->post['telr_purdesc']) > 63) {
			$this->error['purdesc'] = $this->language->get('error_purdesc');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function version_ok() {
		if (version_compare(VERSION, '2.3.0.0', '<')) {
			return false;
		}
		return true;
	}

}
?>
