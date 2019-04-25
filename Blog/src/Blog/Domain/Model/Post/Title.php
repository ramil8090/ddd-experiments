<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 19.04.19
 * Time: 15:23
 */

namespace Blog\Domain\Model\Post;


use Assert\Assertion;

class Title
{
    const MAX_LENGTH = 255;

    private $title;

    public function __construct(string $title)
    {
        $this->setTitle($title);
    }

    private function setTitle(string $title): void
    {
        Assertion::notEmpty($title);
        Assertion::max(strlen($title), self::MAX_LENGTH);

        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function __toString()
    {
        return $this->title;
    }
}