<?php
class Migration_Add_posts extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'post_id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'post_title_nl' => array(
                'type' => 'VARCHAR',
                'constraint' => '175',
            ),
            'post_title_en' => array(
                'type' => 'VARCHAR',
                'constraint' => '175',
            ),
            'post_text_nl' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'post_text_en' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'post_image' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'post_timestamp' => array(
                'type' => 'TIMESTAMP',
            )
        ));
        $this->dbforge->add_key('post_id', TRUE);
        $this->dbforge->create_table('posts');
    }

    public function down()
    {
        $this->dbforge->drop_table('posts');
    }
}