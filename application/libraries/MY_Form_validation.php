<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

    /**
     * Error Array
     *
     * Returns the error messages as an array
     *
     * @return  array
     */
	function error_array()
	{
		if (count($this->_error_array) === 0)
		{
			return array();
		}
		return $this->_error_array;
	}

	// --------------------------------------------------------------------

	/**
     * First Error
     *
     * Returns the first error message
     *
     * @return  string
     */
	function first_error()
	{
		return reset($this->error_array());
	}

	// --------------------------------------------------------------------

	/**
	 * Return the data after validation
	 *
	 * @return	array
	 */
	public function field_data($field = NULL)
	{
		if($field)
		{
			return isset($_POST[$field]) ? $_POST[$field] : '';
		}

		$return = array();
		if(is_array($this->_field_data))
		{
			foreach($this->_field_data as $key => $val)
			{
				$return[$key] = isset($_POST[$key]) ? $_POST[$key] : '';
			}
		}
		return $return;
	}

	// --------------------------------------------------------------------

	/**
	 * Match one field to another
	 *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	public function not_unique($str, $field)
	{
		list($table, $field)=explode('.', $field);
		$query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
		return $query->num_rows() !== 0;
    }

	// --------------------------------------------------------------------

	/**
	 * Format a date
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function format_date($str,$format)
	{
		return date($format,strtotime($str));
	}

	// --------------------------------------------------------------------

	/**
	 * Is a valid Date for MySQL
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function valid_date($str)
	{
		return date('Y-m-d',strtotime($str));
	}

	// --------------------------------------------------------------------

	/**
	 * Is a valid Time for MySQL
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function valid_time($str)
	{
		return date('H:i:s',strtotime($str));
	}

	// --------------------------------------------------------------------

	/**
	 * Is a valid Year
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function valid_year($str)
	{
		//var_dump(1995 < $str && $str < 2001); exit;
		return preg_match('/^\d{4}$/', $str) === 1;
	}

	// --------------------------------------------------------------------

	/**
	 * Required Array
	 *
	 * @access	public
	 * @param	string
	 * @param	integer
	 * @return	bool
	 */
	public function required_list($arr, $range)
	{
		$range = explode('-',$range);
		$min = $range[0];
		$max = $range[1];
		if(is_array($arr) && $min<=count($arr) && count($arr)<=$max)
		{
			for($i = 0, $count = 0; $i < count($arr); $i++)
				if($arr[$i] && is_string($arr[$i]))
					$count++;
			return $min <= $count && $count <= $max;
		}
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Valid Password
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function valid_password($str)
	{
		return preg_match('/([0-9]+[a-zA-Z]+|[a-zA-Z]+[0-9]+)[0-9a-zA-Z]*$/',$str) === 1;
	}

	// --------------------------------------------------------------------

	/**
	 * Run the Validator for service
	 *
	 * This function does all the work.
	 *
	 * @access	public
	 * @return	bool
	 */
	public function service($group = '')
	{
		$run = $this->run($group);
		$this->CI->data['status'] = 'FAILURE';
		if($this->first_error()) $this->CI->data['msg'] = $this->first_error();
		return $run;
	}

}