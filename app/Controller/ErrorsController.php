<?php 
class ErrorsController extends AppController {
    public $name = 'Errors';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('error404');
        $this->layout = 'default-errors';
    }

    public function error404() {
		$this->response->statusCode(404);
    }
}