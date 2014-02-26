<?php
App::uses('AppModel', 'Model');
/**
 * NaturesLegal Model
 *
 * @property User $User
 */
class NaturesLegal extends AppModel {
	/**
	* Table
	*
	* @var string
	*/
	public $useTable = 'natures_legal';

	/**
	* Virtual fields
	*
	* @var string
	*/
	public $virtualFields = array(
    	'name_list' => "CONCAT(NaturesLegal.code, ' - ', NaturesLegal.name)",
	);	
	
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'name_list';


	/**
	* belongsTo
	*
	* @var array
	*/
	public $belongsTo = array('NaturesStructure');



}