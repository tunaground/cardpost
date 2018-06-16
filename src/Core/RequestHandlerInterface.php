<?php
namespace Tunacan\Core;

use Tunacan\Http\Request;

interface RequestHandlerInterface
{
    public function handle(Request $request);
}
