<?php

/**
 * 2015 inTarget
 * @author    inTarget RU <https://intarget.ru/>
 * @copyright 2015 inTarget RU
 * @license   GNU General Public License, version 2
 */

class ControllerModuleIntarget extends Controller {
	private $error = array();
	private $name = "intarget";
	private $ver = '1.0.1';

	public function index() {
		$this->load->language('module/intarget');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('intarget', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_email'] = $this->language->get('entry_text_email');
		$data['text_key'] = $this->language->get('entry_text_key');
		$data['button_apply'] = $this->language->get('entry_title_apply');
		$data['auth'] = $this->language->get('entry_auth');
		$data['email_placeholder'] = $this->language->get('entry_email_placeholder');
		$data['key_placeholder'] = $this->language->get('entry_key_placeholder');
		$data['tech_support'] = $this->language->get('entry_tech_support');

		$data['succ_mess1'] = $this->language->get('entry_succ_mess1');
		$data['succ_mess2'] = $this->language->get('entry_succ_mess2');

		$data['url'] = HTTP_CATALOG;
		$data['ver'] = $this->ver;
		$data['projectId'] = '';
		$data['error_warning'] = '';

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/intarget', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$settings = $this->config->get('intarget');

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['intarget']['email'];
		} elseif (isset($settings['email'])) {
			$data['email'] = $settings['email'];
		} else {
			$data['email'] = '';
		}
		if (isset($this->request->post['key'])) {
			$data['key'] = $this->request->post['intarget']['key'];
		} elseif (isset($settings['key'])) {
			$data['key'] = $settings['key'];
		} else {
			$data['key'] = '';
		}
		if (isset($this->request->post['url'])) {
			$data['url'] = $this->request->post['intarget']['url'];
		} elseif (isset($settings['url'])) {
			$data['url'] = $settings['url'];
		} else {
			$data['url'] = HTTP_CATALOG;
		}
		if (isset($settings['projectId'])) {
			$data['projectId'] = $settings['projectId'];
		} else {
			$data['projectId'] = '';
		}

		$data['action'] = $this->url->link('module/'.$this->name, 'token=' . $this->session->data['token'], 'SSL');
		$data['action_register'] = str_replace('&amp;', '&', $this->url->link('module/'.$this->name.'/register', 'token=' . $this->session->data['token'], 'SSL'));
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/intarget' . '.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/'.$this->name)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

	public function register() {

		$json = array();
		$this->load->language('module/'. $this->name);

		$domain = 'intarget-dev.lembrd.com';
		$settings = $this->request->post[$this->name];
		$email = $settings['email'];//'s-m-o-k@list.ru';
		$key = $settings['key'];//'IolyfSM7oq3Ts62CPL7QCjou0hfmna8R';
		$url = $settings['url'];//'http://opencart20.orcart.ru';

		if (($email == '') OR ($key == '')) {
			$json['result'] = 'error';
			$json['text'] = $this->language->get('text_error_empty');
			$json['code'] = 400;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$ch = curl_init();

		$jsondata = json_encode(array(
			'email' => $email,
			'key' => $key,
			'url' => $url,
			'cms' => 'opencart'));

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/json'));

		curl_setopt($ch, CURLOPT_URL, "http://" . $domain . "/api/registration.json");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec($ch);

		$json_result = json_decode($server_output);
		if (isset($json_result->status)) {
			if (($json_result->status == 'OK') && (isset($json_result->payload))) {
				if (isset($json_result->payload->projectId)){
					$settings['projectId'] = $json_result->payload->projectId;

					$this->load->model('setting/setting');
					$this->model_setting_setting->editSetting($this->name, array($this->name => $settings));

					$json['result'] = 'success';
					$json['text'] = sprintf($this->language->get('text_register_success'), $json_result->payload->projectId);
					$json['projectId'] = $json_result->payload->projectId;
					$json['code'] = $json_result->code;

				}
				$query = $this->db->query("SELECT value FROM ". DB_PREFIX ."setting WHERE `key`='config_google_analytics'");
				$google_code = $query->row['value'];
				if (!strpos($google_code, 'INTARGET CODE START')) {
					$google_code .= "
&lt;!-- INTARGET CODE START --&gt;
  &lt;script type=&quot;text/javascript&quot;&gt;
    (function(d, w, c) {
      w[c] = {
        projectId: ".$json_result->payload->projectId."
      };

      var n = d.getElementsByTagName(&quot;script&quot;)[0],
      s = d.createElement(&quot;script&quot;),
      f = function () { n.parentNode.insertBefore(s, n); };
      s.type = &quot;text/javascript&quot;;
      s.async = true;
      s.src = &quot;//intarget-dev.lembrd.com/utlanalytics/loader.js&quot;;

      if (w.opera == &quot;[object Opera]&quot;) {
        d.addEventListener(&quot;DOMContentLoaded&quot;, f, false);
      } else { f(); }

    })(document, window, &quot;inTargetInit&quot;);
  &lt;/script&gt;
    &lt;!-- INTARGET CODE END --&gt;
  ";
					$this->db->query("UPDATE ". DB_PREFIX ."setting SET `value`='". $google_code ."' WHERE `key`='config_google_analytics'");
				}

			} elseif ($json_result->status = 'error') {
				$json['result'] = 'error';
				$json['text'] = sprintf($this->language->get('text_register_unsuccess'), $json_result->code);
				$json['code'] = $json_result->code;
			}
		}
		curl_close($ch);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function uninstall() {
		$query = $this->db->query("SELECT value FROM ". DB_PREFIX ."setting WHERE `key`='config_google_analytics'");
		$google_code = $query->row['value'];
		$start_text = '&lt;!-- INTARGET CODE START --&gt;';
		$end_text = '&lt;!-- INTARGET CODE END --&gt;';
		$start = 0;
		$end = 0;
		$new_google_code = '';
		$start = strpos($google_code, $start_text);
		if ($start !== false) {
			if ($start >= 2) $start -= 2;
			$end = strpos($google_code, $end_text);
			if ($end !== false) {
				$end += strlen($end_text);
				$new_google_code .= substr($google_code, 0, $start);
				$new_google_code .= substr($google_code, $end);
				$this->db->query("UPDATE ". DB_PREFIX ."setting SET `value`='". $new_google_code ."' WHERE `key`='config_google_analytics'");
			}
		}
	}
}
?>
