<?php
class Migration_Add_cancel_deadline extends CI_Migration {
    public function up(){
        $fields = array(
            'afmelddeadline' => array(
                'type'  => 'DATE',
                'after' => 'inschrijfdeadline',
                'null'  => true,
            )
        );
        $this->dbforge->add_column('agenda', $fields);
    }

    public function down(){
        $this->dbforge->drop_column('agenda', 'afmelddeadline');
    }
}