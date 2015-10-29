<?php
class ControllerModuleintarget extends Controller {
	private $error = array();
	private $name = "intarget";
	
	public function index() {
		$data = $this->load->language('module/'. $this->name);

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($this->name, $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
   			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/'.$this->name, 'token=' . $this->session->data['token'], 'SSL'),
   		);

		$data['action'] = $this->url->link('module/'.$this->name, 'token=' . $this->session->data['token'], 'SSL');
		$data['action_register'] = str_replace('&amp;', '&', $this->url->link('module/'.$this->name.'/register', 'token=' . $this->session->data['token'], 'SSL'));
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$settings = $this->config->get($this->name);


		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post[$this->name]['email'];
		} elseif (isset($settings['email'])) {
			$data['email'] = $settings['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['key'])) {
			$data['key'] = $this->request->post[$this->name]['key'];
		} elseif (isset($settings['key'])) {
			$data['key'] = $settings['key'];
		} else {
			$data['key'] = '';
		}

		if (isset($this->request->post['url'])) {
			$data['url'] = $this->request->post[$this->name]['url'];
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
		if(isset($this->request->post[$this->name . '_status'])) {
			$data[$this->name . '_status'] = $this->request->post[$this->name . '_status'];
		} else {
			$data[$this->name . '_status'] = $this->config->get($this->name . '_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/' . $this->name . '.tpl', $data));
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

		$domain = 'dev.intarget.ru';
		$settings = $this->request->post[$this->name];
		$email = $settings['email'];//'s-m-o-k@list.ru';
		$key = $settings['key'];//'IolyfSM7oq3Ts62CPL7QCjou0hfmna8R';
		$url = $settings['url'];//'http://opencart20.orcart.ru';

		if (($domain == '') OR ($email == '') OR ($key == '') OR ($url == '')) {
			return;
		}

		$ch = curl_init();

		$jsondata = json_encode(array(
			'email' => $email,
			'key' => $key,
			'url' => $url));

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
      s.src = &quot;//dev.lembrd.com/utlanalytics/loader.js&quot;;

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
