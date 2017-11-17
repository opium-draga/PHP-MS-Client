<?php
/**
 * Created by PHPStorm.
 * User: Serhii Kondratovec
 * Email: sergey@spheremall.com
 * Date: 13.10.2017
 * Time: 20:02
 */

namespace SphereMall\MS\Tests\Resources\Users;

use SphereMall\MS\Entities\User;
use SphereMall\MS\Lib\Specifications\Users\IsUserEmail;
use SphereMall\MS\Lib\Specifications\Users\IsUserSubscriber;
use SphereMall\MS\Tests\Resources\SetUpResourceTest;

class UsersResourceTest extends SetUpResourceTest
{
    #region [Test methods]
    public function testServiceGetList()
    {
        $users = $this->client->users();
        $all = $users->all();

        foreach ($all as $item) {
            $this->assertInstanceOf(User::class, $item);
        }
    }

    public function testSubscribeUserIfNotExistOrNotSubscriber()
    {
        $email = 'test@test.com';

        $users = $this->client->users();
        $userList = $users->filter(new IsUserEmail($email))
                          ->limit(1)
                          ->all();

        if (!isset($userList[0]) || !(new IsUserSubscriber())->isSatisfiedBy($userList[0])) {
            $this->assertTrue($users->subscribe($email));
        }

        $userList = $users->filter(new IsUserEmail($email))
                          ->limit(1)
                          ->all();

        $this->assertEquals(1, $userList[0]->isSubscriber);
        $this->assertTrue($users->delete($userList[0]->id));

    }

    public function testSubscribeUserIfExistAndSubscribe()
    {
        $email = 'test@test.com';

        $users = $this->client->users();

        $user = $users->create([
            'email'        => $email,
            'isSubscriber' => 1
        ]);

        $this->assertEquals(1, $user->isSubscriber);
        $this->assertEquals($email, $user->email);

        $this->assertFalse($users->subscribe($email));
        $this->assertTrue($users->delete($user->id));

    }

    public function testUnsubscribeUser()
    {

        $email = 'test@test.com';

        $users = $this->client->users();

        $user = $users->create([
            'email'        => $email,
            'isSubscriber' => 1
        ]);

        $this->assertTrue($users->unsubscribe($user->id));
        $this->assertTrue($users->delete($user->id));
    }

    public function testUnsubscribeUserIfNotExistOrNotSubscriber()
    {
        $email = 'test@test.com';

        $users = $this->client->users();
        $userList = $users->filter(new IsUserEmail($email))
                          ->limit(1)
                          ->all();

        if (!isset($userList[0]) || !(new IsUserSubscriber())->isSatisfiedBy($userList[0])) {
            $this->assertFalse($users->unsubscribe(0));
        }

    }

    #endregion
}
