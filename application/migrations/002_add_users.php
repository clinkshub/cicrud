<?php

class Migration_Add_users extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'auto_increment' => true,
            ),
            'emailid' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ),
            'mobileno' => array(
                'type' => 'BIGINT',
                'null' => true,
            ),
            'fullname' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ),
            'country' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ),
            'city' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ),
            'zip' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ),
            'address1' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ),
            'address2' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->dbforge->create_table('users');
    }

    public function down()
    {
        $this->dbforge->drop_table('users');
    }
}
