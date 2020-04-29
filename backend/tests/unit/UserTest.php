<?php 

namespace backend\tests;

class UserTest extends \Codeception\Test\Unit
{
    public $user;
    
    /**
     * @var \backend\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        $this->user = $this->make('common\models\db\User', ['id' => 1, 'username' => 'sergmoro1']);
        $this->make('common\models\db\Profile', ['model' => User::INT_CODE, 'parent_id' => 1, 'firstname' => 'Sergey', 'lastname' => 'Morozov']);
    }

    protected function _after()
    {
    }

    // tests
    public function testFullName()
    {
        $this->assertTrue($user->profile->fullName, 'Sergey Morozov');
    }
}
