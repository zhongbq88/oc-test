<?php
class ControllerCommonPolicy extends Controller {
	public function index() {
		$this->response->setOutput($this->load->view('common/policy', ''));
	}
}
