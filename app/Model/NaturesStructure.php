<?php
App::uses('AppModel', 'Model');
/**
 * NaturesStructure Model
 *
 * @property User $User
 */
class NaturesStructure extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'name';

	/**
	* Table
	*
	* @var string
	*/
	public $useTable = 'natures_structure';

	/**
	* hasMany
	*
	* @var array
	*/
	public $hasMany = array('NaturesStructure');



}