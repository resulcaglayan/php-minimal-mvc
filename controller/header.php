<?php
class ControllerHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();

		return $this->load->view('header', $data);
	}
}