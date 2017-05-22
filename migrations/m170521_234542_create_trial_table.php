<?php

class m170521_234542_create_trial_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('trial', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'name' => 'varchar(64) collate utf8_bin NOT NULL',
            'description' => 'varchar(64) collate utf8_bin',
            'owner_user_id' => 'int(10) unsigned NOT NULL',
            'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
            'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
            'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
            'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
            'PRIMARY KEY (`id`)',
            'KEY `trial_owner_user_id_fk` (`owner_user_id`)',
            'KEY `trial_last_modified_user_id_fk` (`last_modified_user_id`)',
            'KEY `trial_created_user_id_fk` (`created_user_id`)',
            'CONSTRAINT `trial_owner_user_id_fk` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)',
            'CONSTRAINT `trial_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
            'CONSTRAINT `trial_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
        );
    }

    public function down()
    {
        $this->dropTable('trial');
    }
}
