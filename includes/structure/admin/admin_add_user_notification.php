<?php

namespace ptoffice\classes;

/**
 * Custom Email Template 
 * */
class EmailTemplate
{

	/**
	 * Array or String of emails where to send
	 * @var mixed
	 */
	protected $emails;
	protected $headers;

	/**
	 * Subject of email
	 * @var string
	 */
	protected $title;

	/**
	 * Associative Array of dynamic data
	 * @var array
	 */
	protected $dynamicData = array();

	/**
	 * Template used to send data
	 * @var string
	 */
	protected $template;

	/**
	 * Prepared template with real data instead of placeholders
	 * @var string
	 */
	protected $outputTemplate;

	/**
	 * Intialize instances
	 * @since    1.0.0
     * @access   public
	* */
	public function __construct($emails, $title, $dynamicData, $template){
		$this->emails = $emails;
		$this->title = $title;
		$this->headers = '';
		$this->dynamicData = $dynamicData;
		$this->template = $template;
		$this->prepareTemplate();
		$this->send();
	}

	/**
	 * Prepare Email Template	 
	 * @since    1.0.0
     * @access   public
	 **/
	private function prepareTemplate(){
		$this->template = $this->getTemplate();
		$search = array('{{Name}}', '{{Email}}');
		$data = $this->dynamicData;
		$username = $data['username'];
		$useremail = $data['useremail'];
		// Placeholder used in our template
		$replace_with = array($username, $useremail);
		$template1 = str_replace($search, $replace_with, $this->template);
		$this->outputTemplate = $template1;
	}

	/**
	 * Get Email Template
	 * @since    1.0.0
     * @access   public
	**/
	private function getTemplate(){
		return get_option($this->template);
	}

	/**
	 * Send Email Function
	 * @since    1.0.0
     * @access   public
	*/
	private function send(){
		if (!empty($this->emails) && !empty($this->title) && !empty($this->outputTemplate)) {
			wp_mail($this->emails, $this->title, $this->outputTemplate, 'Content-Type: text/html; charset=UTF-8');
		}
	}
}
