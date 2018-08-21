<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_subdomains extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
                        'id' => array(
                                'type' => 'INT',
                                'constraint' => 5,
                                'unsigned' => true,
                                'auto_increment' => true,
                        ),
                        'subdomain' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                        ),
                        'description' => array(
                'type' => 'VARCHAR',
                                'constraint' => '255',
                                'null' => true,
                        ),
                        'created_on' => array(
                'type' => 'DATETIME',
                'null' => true,
                        ),
                        'created_by' => array(
                'type' => 'INT',
                'null' => false,
                        ),
                        'updated_on' => array(
                                'type' => 'DATETIME',
                                'null' => true,
            ),
            'updated_by' => array(
                'type' => 'INT',
                'null' => true,
            ),
                        'active' => array(
                'type' => 'TINYINT',
                'null' => true,
                'default' => 0,
                        ),
                        'deleted' => array(
                'type' => 'TINYINT',
                'null' => true,
                'default' => 0,
                        ),
                ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('subdomains');
    }

    public function down()
    {
        $this->dbforge->drop_table('subdomains');
    }
}
