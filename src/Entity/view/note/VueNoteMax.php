<?php

namespace App\Entity\view\note;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'vue_notes_max_from_derniere')]
class VueNotesMax extends BaseVueNote
{
}