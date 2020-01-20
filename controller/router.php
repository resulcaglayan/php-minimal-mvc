<?php
class ControllerRouter extends Controller {
	public function index() {

		if (isset($this->request->get['store']) && $this->request->get['store'] != 'router') {
			$store = $this->request->get['store'];
		} else {
			$store = $this->config->get('action_default');
		}
		
		$data = array();
		$store = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string) $store);
		$action = new Action($store);
		$output = $action->execute($this->registry, $data);
		return $output;
	}
}
