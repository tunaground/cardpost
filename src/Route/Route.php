<?php
namespace Tunacan\Route;

use Tunacan\Http\Request;

class Route implements RouteInterface
{
    private $path;
    private $controllerFqn;
    private $method;
    private $options;
    private $arguments;
    private $redirect;

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path = null): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getControllerFqn(): string
    {
        return $this->controllerFqn;
    }

    /**
     * @param string $controllerFqn
     */
    public function setControllerFqn(string $controllerFqn = null): void
    {
        $this->controllerFqn = $controllerFqn;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method = null): void
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = null): void
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return ($this->arguments)?: [];
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments = null): void
    {
        $this->arguments = $arguments;
    }

    public function isRedirect(): bool
    {
        return (!is_null($this->redirect));
    }

    /**
     * @return mixed
     */
    public function getRedirect(): string
    {
        return $this->redirect;
    }

    /**
     * @param mixed $redirect
     */
    public function setRedirect($redirect = null): void
    {
        $this->redirect = $redirect;
    }

    public function match(Request $request) {
        $connectPath = preg_replace('/[\/]+/', '/', $request->getServerInfo('REQUEST_URI'));
        $explodedRoutePath = explode('/', $this->path);
        $explodedConnectPath = explode('/', $connectPath);
        if (sizeof($explodedRoutePath) != sizeof($explodedConnectPath)) {
            return false;
        }
        for ($i = 0; $i < sizeof($explodedRoutePath); $i++) {
            if (preg_match('/{[a-zA-Z0-9]+}/', $explodedRoutePath[$i]) !== 0) {
                $this->arguments[substr($explodedRoutePath[$i], 1, -1)] = $explodedConnectPath[$i];
            }
            if (preg_match('/{[a-zA-Z0-9]+}/', $explodedRoutePath[$i]) === 0
                && $explodedRoutePath[$i] !== $explodedConnectPath[$i]
            ) {
                return false;
            }
        }
        return true;
    }
}
