<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Service\Slugifier;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public const POST_TITLES = [
        'Man must explore, and this is exploration at its greatest',
        '100 miles on the tracks of the Calusa',
        'Failure is not an option',
        'Me, love and other catastrophes',
        'Rocky Mountain High',
        'Life and death in the empire of the tiger',
        'Couchsurfing in Iran',
        'The end of a journey',
        'Happy Antartica',
        'The long road to water'
    ];

    public function __construct(private Slugifier $slugifier)
    {}

    public function load(ObjectManager $manager): void
    {
        foreach(self::POST_TITLES as $postId => $postTitle){
            $post = (new Post())
                ->setTitle($postTitle)
                ->setSynopsis('Aenean pellentesque molestie interdum. Proin in tincidunt dolor, in elementum dui.')
                ->setContent('
                <p class="text-2xl md:text-3xl mb-5">Praesent quis nisi non justo efficitur sagittis sed sit amet felis.</p>
                <p class="py-6">Sed dignissim lectus ut tincidunt vulputate. Fusce tincidunt lacus purus, in mattis tortor sollicitudin pretium. Phasellus at diam posuere, scelerisque nisl sit amet, tincidunt urna. Cras nisi diam, pulvinar ut molestie eget, eleifend ac magna. Sed at lorem condimentum, dignissim lorem eu, blandit massa. Phasellus eleifend turpis vel erat bibendum scelerisque. Maecenas id risus dictum, rhoncus odio vitae, maximus purus. Etiam efficitur dolor in dolor molestie ornare. Aenean pulvinar diam nec neque tincidunt, vitae molestie quam fermentum. Donec ac pretium diam. Suspendisse sed odio risus. Nunc nec luctus nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Duis nec nulla eget sem dictum elementum.</p>
                <blockquote class="border-l-4 border-green-500 italic my-8 pl-8 md:pl-12">Example of blockquote - Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam at ipsum eu nunc commodo posuere et sit amet ligula.</blockquote>
                <ol>
                <li class="py-3">Maecenas accumsan lacus sit amet elementum porta. Aliquam eu libero lectus. Fusce vehicula dictum mi. In non dolor at sem ullamcorper venenatis ut sed dui. Ut ut est quam. Suspendisse quam quam, commodo sit amet placerat in, interdum a ipsum. Morbi sit amet tellus scelerisque tortor semper posuere.</li>
                <li class="py-3">Morbi varius posuere blandit. Praesent gravida bibendum neque eget commodo. Duis auctor ornare mauris, eu accumsan odio viverra in. Proin sagittis maximus pharetra. Nullam lorem mauris, faucibus ut odio tempus, ultrices aliquet ex. Nam id quam eget ipsum luctus hendrerit. Ut eros magna, eleifend ac ornare vulputate, pretium nec felis.</li>
                <li class="py-3">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc vitae pretium elit. Cras leo mauris, tristique in risus ac, tristique rutrum velit. Mauris accumsan tempor felis vitae gravida. Cras egestas convallis malesuada. Etiam ac ante id tortor vulputate pretium. Maecenas vel sapien suscipit, elementum odio et, consequat tellus.</li>
                </ol>')
                ->setSlug($this->slugifier->slugify($postTitle));
            // Random timestamp between 01/01/2020 and 01/07/2022
            $randTimestamp = mt_rand(1577833200, 1656626400);
            $post->setCreatedAt((new DateTime())->setTimestamp($randTimestamp));
            // Random category
            $randCategoryId = rand(0,4);
            $post->setCategory($this->getReference("category_$randCategoryId"));

            // References : category_0, category_1, category_2 ...
            $this->addReference("post_$postId", $post);

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }
}
