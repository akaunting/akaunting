<?php

namespace Laravel\Prompts;

class Key
{
    const UP = "\e[A";

    const DOWN = "\e[B";

    const RIGHT = "\e[C";

    const LEFT = "\e[D";

    const UP_ARROW = "\eOA";

    const DOWN_ARROW = "\eOB";

    const RIGHT_ARROW = "\eOC";

    const LEFT_ARROW = "\eOD";

    const DELETE = "\e[3~";

    const BACKSPACE = "\177";

    const ENTER = "\n";

    const SPACE = ' ';

    const TAB = "\t";

    const SHIFT_TAB = "\e[Z";

    const CTRL_C = "\x03";
}
