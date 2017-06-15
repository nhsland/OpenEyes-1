<?php

class m170609_005053_add_primary_investigator_roles extends CDbMigration
{
    const PRIMARY_INVESTIGATOR_ROLE = "Primary Investigator";
    const ADMINISTER_TRIALS_TASK = "TaskAdministerTrials";

    public function up()
    {
        $this->insert('authitem', array('name' => self::ADMINISTER_TRIALS_TASK, 'type' => 1));
        $this->insert('authitemchild', array('parent' => self::PRIMARY_INVESTIGATOR_ROLE, 'child' => self::ADMINISTER_TRIALS_TASK));
    }

    public function down()
    {
        $this->delete('authitemchild', 'parent = "' . self::PRIMARY_INVESTIGATOR_ROLE . '" AND child = "' . self::ADMINISTER_TRIALS_TASK . '"');
        $this->delete('authitem', 'name = "' . self::ADMINISTER_TRIALS_TASK . '"');
    }
}