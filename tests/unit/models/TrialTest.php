<?php

class TrialTest extends CDbTestCase
{
    public $fixtures = array(
        'user' => 'User',
        'trial' => 'Trial',
        'patient' => 'Patient',
        'trial_patient' => 'TrialPatient',
        'user_trial_permission' => 'UserTrialPermission',
    );

    public static function setupBeforeClass()
    {
        Yii::app()->getModule('OETrial');
    }

    public function setUp()
    {
        parent::setUp();
    }

    public function testTitle()
    {
        $trial = new Trial();
        $trial->name = null;
        $this->assertFalse($trial->save(), 'A Trial cannot be saved with a null name');
    }

    public function testCreatedDate()
    {
        $trial = new Trial();
        $trial->created_date = date('Y-m-d', strtotime('2012-12-21'));
        $this->assertEquals($trial->getCreatedDateForDisplay(), '21 Dec 2012');

        $trial->created_date = date('Y-m-d', strtotime('1970-1-1'));
        $this->assertEquals($trial->getCreatedDateForDisplay(), '1 Jan 1970');
    }

    public function testDataProvidersExist()
    {
        $providers = $this->trial('trial1')->getPatientDataProviders();
        $this->assertArrayHasKey(TrialPatient::STATUS_ACCEPTED, $providers);

        $this->assertGreaterThan(0, count($providers));

        foreach (TrialPatient::getAllowedStatusRange() as $status) {
            $this->assertArrayHasKey($status, $providers);
        }
    }

    public function testDataProviderContent()
    {
        $providers = $this->trial('trial1')->getPatientDataProviders();

        /* @var CActiveDataProvider $shortlistedPatientProvider */
        $shortlistedPatientProvider = $providers[TrialPatient::STATUS_SHORTLISTED];
        $data = $shortlistedPatientProvider->getData();
        $this->assertCount(2, $data);
    }

    public function testNoPatientsInDataProvider()
    {
        $providers = $this->trial('trial2')->getPatientDataProviders();

        /* @var CActiveDataProvider $shortlistedPatientProvider */
        $shortlistedPatientProvider = $providers[TrialPatient::STATUS_SHORTLISTED];
        $data = $shortlistedPatientProvider->getData();
        $this->assertCount(0, $data);
    }

    public function testHasShortlistedPatients()
    {
        $this->assertTrue($this->trial('trial1')->hasShortlistedPatients());
        $this->assertFalse($this->trial('trial2')->hasShortlistedPatients());
    }

    public function testCheckTrialAccessManage()
    {
        $this->assertTrue(Trial::checkTrialAccess($this->user('user1'), $this->trial('trial1')->id,
            UserTrialPermission::PERMISSION_VIEW), 'user1 should have view access to trial1');

        $this->assertTrue(Trial::checkTrialAccess($this->user('user1'), $this->trial('trial1')->id,
            UserTrialPermission::PERMISSION_EDIT), 'user1 should have edit access to trial1');

        $this->assertTrue(Trial::checkTrialAccess($this->user('user1'), $this->trial('trial1')->id,
            UserTrialPermission::PERMISSION_MANAGE), 'user1 should have manage access to trial1');
    }

    public function testCheckTrialAccessView()
    {
        $this->assertTrue(Trial::checkTrialAccess($this->user('user2'), $this->trial('trial1')->id,
            UserTrialPermission::PERMISSION_VIEW), 'user2 should have view access to trial1');

        $this->assertFalse(Trial::checkTrialAccess($this->user('user2'), $this->trial('trial1')->id,
            UserTrialPermission::PERMISSION_EDIT), 'user2 should not have edit access to trial1');

        $this->assertFalse(Trial::checkTrialAccess($this->user('user2'), $this->trial('trial1')->id,
            UserTrialPermission::PERMISSION_MANAGE), 'user2 should not have manage access to trial1');
    }

    public function testCheckTrialAccessEdit()
    {
        $this->assertTrue(Trial::checkTrialAccess($this->user('user3'), $this->trial('trial1')->id,
            UserTrialPermission::PERMISSION_VIEW), 'user3 should have view access to trial1');

        $this->assertTrue(Trial::checkTrialAccess($this->user('user3'), $this->trial('trial1')->id,
            UserTrialPermission::PERMISSION_EDIT), 'user3 should not have edit access to trial1');

        $this->assertFalse(Trial::checkTrialAccess($this->user('user3'), $this->trial('trial1')->id,
            UserTrialPermission::PERMISSION_MANAGE), 'user3 should not have manage access to trial1');
    }

    public function testGetTrialAccess()
    {
        /* @var Trial $trial */
        $trial = $this->trial('trial1');
        $this->assertEquals(UserTrialPermission::PERMISSION_MANAGE, $trial->getTrialAccess($this->user('user1')));
        $this->assertEquals(UserTrialPermission::PERMISSION_VIEW, $trial->getTrialAccess($this->user('user2')));
        $this->assertEquals(UserTrialPermission::PERMISSION_EDIT, $trial->getTrialAccess($this->user('user3')));
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}