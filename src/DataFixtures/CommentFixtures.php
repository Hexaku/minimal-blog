<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public const COMMENT_CONTENTS = [
        'This is so inspiring, I would love to travel too !',
        'You are 100% right, keep going !',
        'What is this babbling latin thing written ?',
        'I like your storytelling, this is great narrating.',
        'What is your next destination ?',
        'Maybe we\'ll meet there, I\'m living close to the capital.',
        'I love reading your blog while i\'m on break',
        'Small habits leads to great results !',
        'The landscape looks heavenly !',
        'Care to wild animals, they could be dangerous !'
    ];

    public function load(ObjectManager $manager): void
    {
        // Creating 3 comments for every Post, each one from a random user.
        foreach(PostFixtures::POST_TITLES as $postId => $postTitle){
            for($i=0; $i<3; $i++) {
                $post = $this->getReference("post_$postId");

                $randCommentContentId = rand(0, count(self::COMMENT_CONTENTS)-1);
                $post = $this->getReference("post_$postId");
                $comment = (new Comment())
                    ->setContent(self::COMMENT_CONTENTS[$randCommentContentId])
                    ->setPost($post);

                // Add 1 to 30 days to the comment date after the post's creation date
                $randomDaysNumber = rand(1,30);
                $comment->setCreatedAt($post->getCreatedAt()->add(new DateInterval('P' . $randomDaysNumber . 'D')));
    
                // Random user being author of the comment
                $randUserId = rand(0, count(UserFixtures::USERNAMES)-1);
                $comment->setAuthor($this->getReference("user_$randUserId"));
    
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PostFixtures::class
        ];
    }
}
