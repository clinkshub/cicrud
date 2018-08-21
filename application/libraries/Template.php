<?php

class Template
{
    public $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function load($tpl_view, $body_view = null, $data = null)
    {
        if (!is_null($body_view)) {
            if (file_exists(APPPATH.'views/'.$tpl_view.'/'.$body_view)) {
                $body_view_path = $tpl_view.'/'.$body_view;
            } elseif (file_exists(APPPATH.'views/'.$tpl_view.'/'.$body_view.'.php')) {
                $body_view_path = $tpl_view.'/'.$body_view.'.php';
            } elseif (file_exists(APPPATH.'views/'.$body_view)) {
                $body_view_path = $body_view;
            } elseif (file_exists(APPPATH.'views/'.$body_view.'.php')) {
                $body_view_path = $body_view.'.php';
            } else {
                show_error('Unable to load the requested file: '.$tpl_name.'/'.$view_name.'.php');
            }
            $body = $this->ci->load->view($body_view_path, $data, true);
            if (is_null($data)) {
                $data = array('body' => $body);
            } elseif (is_array($data)) {
                $data['body'] = $body;
            } elseif (is_object($data)) {
                $data->body = $body;
            }
        }
        $this->ci->load->view('templates/'.$tpl_view, $data);
    }
}
