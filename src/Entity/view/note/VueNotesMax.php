<?php

namespace App\Entity\view\note;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'vue_dernieres_notes_valide')]
class VueNotesMax extends BaseVueNote
{
}