<?php

namespace App\Service;

use App\Entity\Comment;

class VerificationComment {

    public function commentaireNonAutorise(Comment $comment) {
        $nonAutorise = [
            'con',
            'connard',
            'idiot',
            'imbécile',
            'stupide',
            'merde',
            'putain',
        ];

        foreach ($nonAutorise as $mot) {
            if (strpos($comment->getContenu(), $mot) !== false) {
                // mot trouvé
                return true;
            }
        }

        return false;

    }
}

