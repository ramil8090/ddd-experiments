<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 19.04.19
 * Time: 17:39
 */

namespace Blog\Domain\Model\Blog;


use Assert\Assertion;

class Title
{
    const MAX_LENGTH = 150;
    const MIN_LENGTH = 10;

    private $title;

    public function __construct(string $title)
    {
        $this->setTitle($title);
    }

    private function setTitle(string $title): void
    {
        Assertion::notEmpty($title);
        Assertion::max(strlen($title), self::MAX_LENGTH);
        Assertion::min(strlen($title), self::MIN_LENGTH);

        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title;
    }
}