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
    const NEW = 'new';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const DELETED = 'deleted';

    private $value;
    private $date;

    private function __construct(string $value, ?\DateTimeImmutable $date = null)
    {
        $this->value = $value;
        $this->date = $date;
    }

    public static function newPost(): self
    {
        return new self(self::NEW, new \DateTimeImmutable());
    }

    public static function approved(): self
    {
        return new self(self::APPROVED, new \DateTimeImmutable());
    }

    public static function rejected(): self
    {
        return new self(self::REJECTED, new \DateTimeImmutable());
    }

    public static function deleted(): self
    {
        return new self(self::DELETED, new \DateTimeImmutable());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function date(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function equals(Status $value): bool
    {
        return $value->value() === $this->value;
    }

    public function __toString()
    {
        return $this->value.';'.$this->date->format('Y-m-d H:i:s');
    }
}