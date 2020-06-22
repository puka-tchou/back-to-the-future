<?php namespace dealers\DealerInterface;

interface DealerInterface
{
    public function getStock(string $part_number): array;
}
