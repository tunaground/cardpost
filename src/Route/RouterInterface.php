<?php
namespace Tunacan\Route;

use Tunacan\Http\Request;

interface RouterInterface
{
    public function getRoute(Request $request): RouteInterface;
}