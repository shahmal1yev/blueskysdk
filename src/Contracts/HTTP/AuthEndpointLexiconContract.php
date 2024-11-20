<?php

namespace Atproto\Contracts\HTTP;

use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;

interface AuthEndpointLexiconContract extends LexiconContract, RequestContract, \SplObserver
{
}
