<?php
/**
 * Created by PhpStorm.
 * User: ramil
 * Date: 16.04.19
 * Time: 15:13
 */

namespace Blog\Domain\Model\Blog;


class Status
{
    const ACTIVE = 'active';
    const DELETED = 'deleted';

    private $status;

    private function __construct($status)
    {
        $this->status = $status;
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
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