<?php
namespace Tunacan\Core;

use Tunacan\Http\Request;
use Tunacan\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request);
}
