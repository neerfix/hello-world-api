<?php

namespace App\DataFixtures;

use App\Services\UserService;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class UsersFixtures extends Fixture
{
    private const DEFAULT_PASSWORD = '1234567890';

    private const USER_NEERFIX = 'neerfix';

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
                ->setIsVerify($userData['isVerify']);

            $manager->persist($userFixture);
            $manager->flush();
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
            ],
        ];

        if (!empty($userName)) {
            return $data[$userName];
        }

        return $data;
    }
}
