<?php namespace dealers\iDealer;

interface iDealer
{
    public function getStock(string $part_number): array;
}
