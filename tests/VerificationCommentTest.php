<?php

namespace App\Tests;

use App\Service\VerificationComment;
use PHPUnit\Framework\TestCase;
use App\Entity\Comment;

class VerificationCommentTest extends TestCase
{

    protected $comment;

    protected function setUp():void 
    {
        $this->comment = new Comment();
    }


    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testcontientMotInterdit() {
        $service = new VerificationComment();
        $this->comment->setContenu("L'auteur est idiot.");

        $result = $service->commentaireNonAutorise($this->comment);

        $this->assertTrue($result);
    }

    public function testNeContientPasMotInterdit() {
        $service = new VerificationComment();
        $this->comment->setContenu("L'auteur est très clair dans ses propos.");

        $result = $service->commentaireNonAutorise($this->comment);

        $this->assertFalse($result);
    }


}
