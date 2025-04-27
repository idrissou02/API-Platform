<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Pret;
use App\Entity\Livre;
use App\Entity\Adherent;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $manager;
    private $faker;
    private $repoLivre;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->faker = Factory::create('fr_FR');
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->repoLivre = $manager->getRepository(Livre::class); // Initialisation du repository
        $this->loadAdherant();
        $this->loadPret();
        $manager->flush();
    }

    /**
     * Création d'adhérents
     * @return void
     */
    public function loadAdherant()
    {
        $genres = ['male', 'female'];
        $commune = ["78003", "78005", "78006", "78007", "78009", "78010", "78013", "78015", "78020",
        "78029", "78030", "78031", "78033", "78034", "78036", "78043", "78048", "78049",
        "78050", "78053", "78057", "78062", "78068", "78070", "78071", "78072", "78073",
        "78076", "78077", "78082", "78084", "78087", "78089", "78090", "78092", "78096",
        "78104", "78107", "78108", "78113", "78117", "78118"];

        for ($i = 0; $i < 25; $i++) {
            $adherant = new Adherent();
            $adherant->setNom($this->faker->lastName())
                ->setPrenom($this->faker->firstName($genres[mt_rand(0, 1)]))
                ->setAdresse($this->faker->streetAddress())
                ->setTel($this->faker->phoneNumber())
                ->setCodeCommune($commune[mt_rand(0, sizeof($commune) - 1)])
                ->setMail(strtolower($adherant->getNom()) . "@gmail.com")
                ->setPassword($this->passwordEncoder->encodePassword($adherant, $adherant->getNom()));
            $this->addReference('adherant' . $i, $adherant);
            $this->manager->persist($adherant);
        }

        $adheradherantAdminant = new Adherent();
        $rolesAdmin[] = ADHERENT::ROLE_ADMIN;
        $adherantAdmin->setNom("Souhil")
                ->setPrenom("Mohamed Idriss")
                ->setMail("admin@gmail.com")
                ->setPassword("Souhil123")
                ->setRoles($rolesAdmin);
        $this->manager->persist($adherantAdmin);

        $this->manager->flush();

        $adherantManager = new Adherent();
        $rolesManager[] = ADHERENT::ROLE_MANAGER;
        $adherantManager->setNom("Simonin")
                ->setPrenom("Cedrik")
                ->setMail("manager@gmail.com")
                ->setPassword("Simonin123")
                ->setRoles($rolesManager);
        $this->manager->persist($adherantManager);

        $this->manager->flush();
    }

    /**
     * Création des prêts
     * @return void
     */
    public function loadPret()
    {
        for ($i = 0; $i < 25; $i++) { // Pour chaque adhérent
            $max = mt_rand(1, 5);
            for ($j = 0; $j < $max; $j++) { // Création des prêts
                $pret = new Pret();
                $livre = $this->repoLivre->find(mt_rand(1, 49));
                $pret->setLivre($livre)
                     ->setAdherent($this->getReference('adherant' . $i))
                     ->setDatePret($this->faker->dateTimeBetween('-6 months'));

                $dateRetourPrevue = date('Y-m-d H:m:n', strtotime('15 days', $pret->getDatePret()->getTimestamp()));
                $dateRetourPrevue = DateTime::createFromFormat('Y-m-d H:m:n', $dateRetourPrevue);
                $pret->setDateRetourPrevue($dateRetourPrevue);

                if (mt_rand(1, 3) == 1) {
                    $pret->setDateRetourReelle($this->faker->dateTimeBetween($pret->getDatePret(), "+30 days"));
                }
                $this->manager->persist($pret);
            }
        }
        $this->manager->flush();
    }
}
