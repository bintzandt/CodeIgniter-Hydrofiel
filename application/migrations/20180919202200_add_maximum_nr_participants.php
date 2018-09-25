<?php
class Migration_Add_maximum_nr_participants extends CI_Migration {
    public function up(){
        $fields = array(
            'maximum' => array(
                'type'  => 'INT',
                'after' => 'afmelddeadline',
                'null'  => FALSE,
                'default'=> 0
            )
        );
        $this->dbforge->add_column('agenda', $fields);
    }

    public function down(){
        $this->dbforge->drop_column('agenda', 'maximum');
    }
}