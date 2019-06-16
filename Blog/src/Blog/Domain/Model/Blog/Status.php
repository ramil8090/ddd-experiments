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
    const ARCHIVED = 'archived';

    /**
     * @var string
     */
    private $status;
    /**
     * @var null | \DateTimeImmutable
     */
    private $date;

    private function __construct($status, ?\DateTimeImmutable $date)
    {
        $this->status = $status;
        $this->date = $date;
    }

    public static function active($date = null): self
    {
        return new self(self::ACTIVE, $date);
    }

    public static function archived($date = null): self
    {
        return new self(self::ARCHIVED, $date);
    }

    public function status(): string
    {
        return $this->status;
    }

    public function date(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function equals(Status $status): bool
    {
        return $status->status() === $this->status;
    }

    public function __toString()
    {
        return $this->status;
    }
}