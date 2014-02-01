<?php
App::uses('Mysql', 'Model/Datasource/Database');
class MysqlNoConstraints extends Mysql {
    public function connect() {
        if (parent::connect()) {
            $this->_connection->exec('SET FOREIGN_KEY_CHECKS = 0;');
            $this->_connection->exec('SET UNIQUE_CHECKS = 0;');
            $this->_connection->exec('SET AUTOCOMMIT = 0;');
            return true;
        }
        return false;
    }
}