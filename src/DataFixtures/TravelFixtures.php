<?php

namespace App\DataFixtures;

use App\Entity\Travel;
use App\Entity\User;
use App\Services\TravelService;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class TravelFixtures extends Fixture
{
    private const BASIC_TRAVEL = 'basic_travel';

    // ------------------------------ >

    public function __construct(
        private TravelService $travelService,
    ) {
    }

    // ------------------------------ >

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        /* @var User $author */

        foreach (static::getData() as $travel => $travelData) {
            $author = $this->getReference($travelData['author']);
            $travelFixture = $this->travelService->create(
                $author,
                $travelData['name'],
                $travelData['budget'],
                $travelData['description'],
                $travelData['startedAt'],
                $travelData['endedAt'],
                $travelData['isSharable']
            );

            $travelFixture
                ->setUuid($travelData['uuid'])
                ->setStatus(Travel::STATUS_ACTIVE);

            $manager->persist($travelFixture);
            $manager->flush();

            $this->addReference($travelFixture->getUuid(), $travelFixture);
        }
    }

    // ------------------------------ >

    public static function getData(?string $travelName = null): array
    {
        $data = [
//            static::BASIC_TRAVEL => [
//                'uuid' => 'UUID_BASIC_TRAVEL',
//                'name' => 'basic_travel',
//                'budget' => 2500.50,
//                'Description' => "I'm doing a wonderful
//                                    travel on a basic location",
//                'startedAt' => new DateTime('2022-04-02'),
//                'endedAt' => new DateTime('2022-04-12'),
//                'isSharable' => true,
//                'author' => 'UUID_NEERFIX',
//            ],
        ];

        if (!empty($travelName)) {
            return $data[$travelName];
        }

        return $data;
    }
}
