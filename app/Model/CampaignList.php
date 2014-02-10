<?php
App::uses('AppModel', 'Model');
/**
 * CampaignList Model
 *
 * @property User $User
 */
class CampaignList extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'title';

	/**
	* belongsTo associations
	*
	* @var array
	*/
	public $belongsTo = array('Client', 'User');
}