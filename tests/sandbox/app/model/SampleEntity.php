<?php

namespace Instante\Tests\Sandbox;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class SampleEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $content;

    /**
     * SampleEntity constructor.
     * @param int $id
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }
}
