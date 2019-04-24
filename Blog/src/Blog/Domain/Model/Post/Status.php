<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 16.04.19
 * Time: 15:13
 */

namespace Blog\Domain\Model\Post;


class Status
{
    const RECENT = 'recent';
    const PUBLISHED = 'published';
    const DELETED = 'deleted';

    private $status;

    private function __construct($status)
    {
        $this->status = $status;
    }

    public static function recent(): self
    {
        return new self(self::RECENT);
    }

    public static function published(): self
    {
        return new self(self::PUBLISHED);
    }

    public static function deleted(): self
    {
        return new self(self::DELETED);
    }

    public function status(): string
    {
        return $this->status;
    }

    public function equal(Status $status): bool
    {
        return $status->status() === $this->status;
    }
}