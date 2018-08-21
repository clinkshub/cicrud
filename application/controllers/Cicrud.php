<?php

class Cicrud extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['fieldtypes'] = array('text', 'radio', 'select', 'checkbox', 'number', 'date', 'textarea');
        $this->table = '';
        $this->retmessage = array('status' => false, 'message' => 'Invalid Request');
        $this->model_file = '';
        $this->controller_file = '';
        $this->view_folder = '';
        $this->model_path = APPPATH.'models/';
        $this->controller_path = APPPATH.'controllers/';
        $this->view_path = APPPATH.'views/';
        $this->load->helper('file');
    }

    /**
     * Load the default Page.
     */
    public function index()
    {
        $this->data['tables'] = $this->db->query("SELECT t.TABLE_NAME AS myTables FROM INFORMATION_SCHEMA.TABLES AS t WHERE t.TABLE_SCHEMA = '".$this->db->database."'")->result_array();

        // get the list of tables available in DB
        $this->load->view('cicrud/Default', $this->data);
    }

    /**
     * get the list of fields available in table.
     */
    public function get_attributes($table)
    {
        $this->table = $table;
        $fields = $this->db->query('DESCRIBE '.$table)->result_array();
        $mdata = array();
        foreach ($fields as $ifield) {
            $mdata[] = $this->getformfield($ifield);
        }
        echo json_encode(array(
            'mdata' => $mdata,
            'fieldtypes' => $this->data['fieldtypes'],
        ));
        //get list of fields available in table
        //return $mdata
    }

    /**
     * handle save post request for
     * all RESOURCES.
     */
    public function save_request()
    {
        return true;
    }

    /**
     * Create model Request only.
     */
    public function save_model_request()
    {
        if ($this->input->post()) {
            $postdata = $this->input->post();
            $this->table = $postdata['tablename'];
            if ($this->check_table() && $this->model_exist() == false) {
                $this->create_model();
                $this->retmessage['message'] = 'Success';
                $this->retmessage['status'] = true;
            }
        }
        echo json_encode($this->retmessage);
    }

    /**
     * Create Controller Request Only.
     */
    public function save_controller_request()
    {
        if ($this->input->post()) {
            $postdata = $this->input->post();
            $this->table = $postdata['tablename'];
            if ($this->check_table() && $this->controller_exist() == false) {
                $this->create_model();
                $this->retmessage['message'] = 'Success';
                $this->retmessage['status'] = true;
            }
        }
        echo json_encode($this->retmessage);
    }

    /**
     * Create Views Request Only.
     */
    public function save_views_request()
    {
        return true;
    }

    /**
     * Create Model.
     * generate the model file here.
     */
    private function create_model()
    {
        //read the template
        $data = '';
        $template = APPPATH.'views/cicrud/templates/model.txt';
        $myfile = fopen($template, 'r');
        $data = fread($myfile, filesize($template));
        $data = str_replace('$class_name$', $this->model_file, $data);
        fclose($myfile);
        //wite the new model file
        $file = $this->model_path.$this->model_file.'.php';
        $fh = fopen($file, 'w');

        if ($fh) {
            fwrite($fh, $data);
            fclose($fh);

            return true;
        }

        return false;
    }

    /**
     * Create Controller.
     */
    public function create_controller($table)
    {
        return true;
    }

    /**
     * Create View files.
     */
    public function create_views($table)
    {
        return true;
    }

    /*
     * Create Jquery resources
     */
    public function create_jquery_requests()
    {
        //return true;
    }

    /**
     * get form field option.
     */
    private function getformfield($column_data)
    {
        $mdata = array();
        $mdata = array(
            'column_name' => $column_data['Field'],
            'column_text' => $this->column_text($column_data['Field']),
            'required' => '',
            'form_field' => '',
        );
        if ($column_data['Key'] == 'PRI') {
            $mdata['form_field'] = 'PRIMARY';

            return $mdata;
        } else {
            //check datatype like char OR varchar OR int OR bigint OR date
            if (strstr($column_data['Type'], 'varchar') || strstr($column_data['Type'], 'char')) {
                $mdata['form_field'] = 'text';
            }
            if (strstr($column_data['Type'], 'bigint') || strstr($column_data['Type'], 'int') || strstr($column_data['Type'], 'smallint') || strstr($column_data['Type'], 'tinyint')) {
                $mdata['form_field'] = 'number';
            }
            if (strstr($column_data['Type'], 'date') || strstr($column_data['Type'], 'timestamp	') || strstr($column_data['Type'], 'datetime')) {
                $mdata['form_field'] = 'date';
            }
            //check NULL for mandatory
            if ($column_data['Null'] == 'NO') {
                $mdata['required'] = 'required';
            }

            //check for set or setnum for radio button
            if (strstr($column_data['Type'], 'set') || strstr($column_data['Type'], 'enum')) {
                $mdata['form_field'] = 'radio';
            }
        }

        return $mdata;
    }

    /*
     *Form views
     */
    private function form_field($column_data)
    {
        $mdata = array();
        $ffdata = '';
        $mdata = array(
            'column_name' => $column_data['Field'],
            'column_text' => $this->column_text($column_data['Field']),
        );
        if ($column_data['Key'] == 'PRI') {
            $mdata['form_field'] = '';

            return $mdata;
        } else {
            //check datatype like char OR varchar OR int OR bigint OR date
            $ffdata = '<input';
            if (strstr($column_data['Type'], 'varchar') || strstr($column_data['Type'], 'char')) {
                $ffdata .= 'type="text" ';
            }
            if (strstr($column_data['Type'], 'bigint') || strstr($column_data['Type'], 'int') || strstr($column_data['Type'], 'smallint') || strstr($column_data['Type'], 'tinyint')) {
                $ffdata .= 'type="number" ';
            }
            if (strstr($column_data['Type'], 'date')) {
                $ffdata .= 'type="date" ';
            }
            //check NULL for mandatory
            if ($column_data['Null'] == 'No') {
                $ffdata .= 'required="" ';
            }
            $ffdata .= 'class="form-control" name="'.$column_data['Field'].'" >';

            //check for set or setnum for radio button
            if (strstr($column_data['Type'], 'set')) {
                $setdata = $column_data['Type'];
                $setdata = str_replace('set(', '', $setdata);
                $setdata = str_replace(')', '', $setdata);
                $setdata = explode(',', $setdata);
                $ffdata = '';
                foreach ($setdata as $idata) {
                    $ffdata .= '<input type="radio" name="'.$column_data['Field'].'" />';
                }
            }
        }
        $mdata['form_field'] = $ffdata;

        return $mdata;
    }

    private function column_text($column)
    {
        $data = $column;
        if (strstr($column, '_')) {
            $column = str_replace('_', ' ', $column);
        }
        $column = ucwords($column);

        return $column;
    }

    /**
     * Check required model exists.
     */
    private function model_exist()
    {
        $this->get_model_file();
        if (file_exists($this->model_path.$this->model_file.'.php')) {
            return true;
        }

        return false;
    }

    /*
     * Check required Controller exists
     */
    private function controller_exist()
    {
        $this->get_controller_file();
        if (file_exists($this->controller_path.$this->controller_file.'.php')) {
            return true;
        }

        return false;
    }

    /**
     * Check Views exist.
     */
    private function view_exist()
    {
        return false;
    }

    /**
     * Check the table exist.
     */
    private function check_table()
    {
        $res = $this->db->table_exists($this->table);
        if ($this->db->table_exists($this->table)) {
            return true;
        }

        return false;
    }

    /**
     * get controller file name.
     */
    private function get_controller_file()
    {
        $table = $this->table;
        $table = str_replace('_', ' ', $table);
        $table = ucwords($table).'';
        $table = str_replace(' ', '', $table);
        $this->model_file = $table;

        return true;
    }

    /**
     * get model file name.
     */
    private function get_model_file()
    {
        $table = $this->table;
        $table = str_replace('_', ' ', $table);
        $table = ucwords($table).'Model';
        $table = str_replace(' ', '', $table);
        $this->model_file = $table;

        return true;
    }

    /**
     * get view folder name.
     */
    private function get_view_folder()
    {
        $table = $this->table;
        $this->view_folder = $table;
    }
}
