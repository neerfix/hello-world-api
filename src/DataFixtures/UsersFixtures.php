<?php

namespace App\DataFixtures;

use App\Services\UserService;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class UsersFixtures extends Fixture
{
    public const DEFAULT_PASSWORD = '1234567890';

    public const USER_NEERFIX = 'neerfix';
    public const USER_FAYAAH = 'fayaah';

    // ------------------------------ >

    public function __construct(
        private UserService $userService,
    ) {
    }

    // ------------------------------ >

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        foreach (static::getData() as $user => $userData) {
            $userFixture = $this->userService->create(
                $userData['email'],
                $userData['username'],
                $userData['password'],
                $userData['birthDate'],
                $userData['firstname'],
                $userData['lastname']
            );

            $userFixture->setRoles($userData['roles'])
                ->setUuid($userData['uuid'])
                ->setIsVerify($userData['isVerify'])
                ->setStatus($userData['status']);

            $manager->persist($userFixture);
            $manager->flush();

            $this->addReference($userData['uuid'], $userFixture);
        }
    }

    // ------------------------------ >

    public static function getData(?string $userName = null): array
    {
        $data = [
            static::USER_NEERFIX => [
                'email' => 'nicolas.notararigo@gmail.com',
                'password' => static::DEFAULT_PASSWORD,
                'username' => 'neerfix',
                'firstname' => 'Nicolas',
                'lastname' => 'Notararigo',
                'birthDate' => new DateTime('1996-12-26'),
                'roles' => ['ROLE_ADMIN'],
                'uuid' => 'UUID-NEERFIX',
                'isVerify' => true,
                'status' => 'active',
            ],
            static::USER_FAYAAH => [
                'email' => 'louise.baulan@hello-world.ovh',
                'password' => static::DEFAULT_PASSWORD,
                'username' => 'fayaah',
                'firstname' => 'Louise',
                'lastname' => 'Baulan',
                'birthDate' => new DateTime('2000-07-26'),
                'roles' => ['ROLE_ADMIN'],
                'uuid' => 'UUID-FAYAAH',
                'isVerify' => true,
                'status' => 'active',
            ],
        ];

        if (!empty($userName)) {
            return $data[$userName];
        }

        return $data;
    }
}
