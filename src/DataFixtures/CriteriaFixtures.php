<?php

namespace App\DataFixtures;

use App\Entity\Criteria;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CriteriaFixtures extends Fixture implements DependentFixtureInterface
{
    const CRITERIAS = [
        'Stock' => [
            'question' => 'Vous gérez vos stocks en flux tendus (peu de stock) ?',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Bio_products_10' => [
            'question' => 'A quel pourcentage approximatif évaluez-vous votre approvisionnement en produits locaux 
            et/ou bio ? entre 0 et 10%',
            'scale' => 0,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Bio_products_30' => [
            'question' => 'A quel pourcentage approximatif évaluez-vous votre approvisionnement en produits locaux 
            et/ou bio ? entre 10 et 30%',
            'scale' => 0,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Bio_products_50' => [
            'question' => 'A quel pourcentage approximatif évaluez-vous votre approvisionnement en produits locaux 
            et/ou bio ? entre 30 et 50%',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Bio_products_65' => [
            'question' => 'A quel pourcentage approximatif évaluez-vous votre approvisionnement en produits locaux 
            et/ou bio ? entre 50 et 65%',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Bio_products_100' => [
            'question' => 'A quel pourcentage approximatif évaluez-vous votre approvisionnement en produits locaux 
            et/ou bio ? entre 65 et 100%',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Recycling' => [
            'question' => 'Faites-vous le tri des déchets ? (verre, plastique, carton ...)',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Energy_light' => [
            'question' => 'Réduisez-vous vos dépenses dépenses énergétiques par l\'utilisation d\'ampoules basses 
            consommation ?',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Energy_water' => [
            'question' => 'Réduisez-vous vos dépenses dépenses énergétiques par l\'utilisation de dispositifs pour 
            réduire les dépenses en eau (mousseur) ?',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Bag' => [
            'question' => 'Proposez-vous le "doggy-bag" ?',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Cleaning_10' => [
            'question' => 'Utilisez-vous des produits écologiques pour le nettoyage ? entre 0 et 10%',
            'scale' => 0,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Cleaning_30' => [
            'question' => 'Utilisez-vous des produits écologiques pour le nettoyage ? entre 10 et 30%',
            'scale' => 0,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Cleaning_50' => [
            'question' => 'Utilisez-vous des produits écologiques pour le nettoyage ? entre 30 et 50%',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Cleaning_65' => [
            'question' => 'Utilisez-vous des produits écologiques pour le nettoyage ? entre 50 et 65%',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Cleaning_100' => [
            'question' => 'Utilisez-vous des produits écologiques pour le nettoyage ? entre 65 et 100%',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Renewable_energies' => [
            'question' => 'Avez-vous un contrat d\'énergie renouvelable ?',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Unsold' => [
            'question' => 'Vos invendus sont-ils mis en vente à moindre coût ? (Too good to go, Opti miam)',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Vegetarian' => [
            'question' => 'Y a-t-il toujours un menu végétarien à votre carte ?',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Biowaste' => [
            'question' => 'Vos biodéchets sont-ils revalorisés ?',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[0],
            ],
        ],
        'Flour' => [
            'question' => 'Votre farine provient-elle d\'un meunier local ?',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[1],
            ],
        ],
        'Bio_bread' => [
            'question' => 'Proposez-vous des pains bio ?',
            'scale' => 100,
            'types' => [
                TypeFixtures::TYPES[1],
            ],
        ],
    ];

    public function load(ObjectManager $manager) : void
    {
        foreach (self::CRITERIAS as $name => $data) {
            $criteria = new Criteria();
            $criteria->setName($name);
            $criteria->setQuestion($data['question']);
            $criteria->setScale($data['scale']);
            foreach (TypeFixtures::TYPES as $type) {
                if (in_array($type, $data['types'])) {
                    $criteria->addType($this->getReference($type));
                }
            }
            $manager->persist($criteria);
        }
        $manager->flush();
    }

    public function getDependencies() : array
    {
        return [TypeFixtures::class];
    }
}
