<?php

class m170521_234542_create_trial_table extends OEMigration
{
    const VERSIONED = true;

    public function up()
    {
        $this->createOETable('trial', array(
            'id' => 'pk',
            'name' => 'varchar(64) collate utf8_bin NOT NULL',
            'description' => 'varchar(64) collate utf8_bin',
            'owner_user_id' => 'int(10) unsigned NOT NULL',
            'status' => 'int(10) unsigned NOT NULL',
        ), self::VERSIONED
        );

        $this->addForeignKey('trial_owner_fk', 'trial', 'owner_user_id', 'user', 'id');
    }

    public function down()
    {
        $this->dropOETable('trial', self::VERSIONED);
    }
}
