<?php

namespace App\Tests\Service;

use App\Service\Slugifier;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SlugifierServiceTest extends KernelTestCase
{
    public function testSlugifier()
    {
        self::bootKernel();
        $slugifier = self::getContainer()->get(Slugifier::class);

        $this->assertEquals('this-is-a-test', $slugifier->slugify('This is a test !'));
        $this->assertEquals('be-careful-about-caps', $slugifier->slugify('Be cArefUl about CAPS'));
        $this->assertEquals('road-745-for-me', $slugifier->slugify('Road 745 for me'));
        $this->assertEquals('trim-was-never-the-problem', $slugifier->slugify(' trim     was never   the problem    '));
        $this->assertEquals('but-accents-can-be-a-problem', $slugifier->slugify('büt àcçënts cän bè a pröblem'));
        $this->assertEquals('too-excited-to-travel', $slugifier->slugify('!!! Too excited to travel !!!!'));
    }
}