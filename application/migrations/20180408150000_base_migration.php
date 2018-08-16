<?php
class Migration_Base_migration extends CI_Migration {

    public function up()
    {
        $agenda_fields = array(
            'event_id' => array(
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ),
            ''
        );
        $this->dbforge->add_key('event_id');
        $this->dbforge->create_table('agenda', $agenda_fields);
    }

    public function down()
    {

    }
}