<?php

namespace Atproto\Contracts\Resources;

interface ObjectContract extends ResponseContract
{
    public function cast();
    public function revert();
}
