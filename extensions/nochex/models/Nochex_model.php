<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

class Nochex_model extends TI_Model {

    public function __construct() {
        parent::__construct();

        $this->load->library('cart');
        $this->load->library('currency');
    }
	
}

/* End of file Nochex.php */
/* Location: ./extensions/Nochex/models/Nochex.php */