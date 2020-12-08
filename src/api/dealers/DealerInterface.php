<?php

namespace BackToTheFuture\dealers;

interface DealerInterface
{
    public function getStock(string $part_number): array;
}
