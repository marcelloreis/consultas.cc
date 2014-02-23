<?php
App::uses('AppModel', 'Model');
/**
 * Campaign Model
 *
 * @property User $User
 */
class Campaign extends AppModel {
	/**
	* Display field
	*
	* @var string
	*/
	public $displayField = 'title';

	/**
	* Virtual fields
	*
	* @var string
	*/
	public $virtualFields = array(
    	'elapsed' => "DATEDIFF(NOW(), Campaign.process_date)",
	);

	/**
	 * Behaviors
	 *
	 * @var string
	 */
    public $actsAs = array(
        'Upload.Upload' => array(
            'source' => array(
                'fields' => array(
                    'dir' => 'source_dir',
                    'type' => 'source_type',
                    'size' => 'source_size',
                )
            )
        )
	);	

	/**
	* hasMany
	*
	* @var array
	*/
	public $hasMany = array('SmsSent');

	/**
	* belongsTo
	*
	* @var array
	*/
	public $belongsTo = array('User', 'Client', 'City', 'State');



}