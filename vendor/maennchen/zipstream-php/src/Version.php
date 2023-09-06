<?php

declare(strict_types=1);

namespace ZipStream;

enum Version: int
{
    case STORE = 0x000A; // 1.00
    case DEFLATE = 0x0014; // 2.00
    case ZIP64 = 0x002D; // 4.50
}
