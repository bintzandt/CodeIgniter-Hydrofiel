<?php

class Migration_Add_facebook_posts extends CI_Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => '175',
                'null' => false,
            ],
            'text' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'image' => [
                'type' => 'TEXT',
                'constraint' => '255',
                'null' => false,
            ],
            'url' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'created' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ];
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('facebook_posts');
    }

    public function down()
    {
        $this->dbforge->drop_table('facebook_posts');
    }
}
