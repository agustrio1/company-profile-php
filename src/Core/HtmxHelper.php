<?php

namespace App\Core;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $content = '';
    private array $data = [];

    public function view(string $view, array $data = []): self
    {
        $this->data = $data;
        
        $request = new Request();
        $isHtmx = $request->header('HX-Request') === 'true';
        
        if ($isHtmx) {
            $this->content = view($view, $data)->render();
            $this->header('Vary', 'HX-Request');
        } else {
            $content = view($view, $data)->render();
            $layoutData = array_merge($data, ['content' => $content]);
            $this->content = view('layouts.app', $layoutData)->render();
        }

        return $this;
    }

    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        echo $this->content;
    }

    public function with(string $key, $value): self
    {
        $_SESSION['flash'][$key] = $value;
        return $this;
    }

    public function withErrors(array $errors): self
    {
        $_SESSION['errors'] = $errors;
        return $this;
    }

    public function withInput(): self
    {
        $_SESSION['old'] = $_POST;
        return $this;
    }

    public function back(): self
    {
        $referrer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->header('Location', $referrer);
        $this->setStatusCode(302);
        return $this;
    }

    public function redirect(string $url): self
    {
        $this->header('Location', $url);
        $this->setStatusCode(302);
        return $this;
    }

    public static function make(): self
    {
        return new self();
    }
}

namespace App\Core;

class HtmxHelper
{
    public static function isHtmxRequest(Request $request): bool
    {
        return $request->header('HX-Request') === 'true';
    }

    public static function render(string $contentView, array $data = [], ?string $layout = 'layouts.app'): string
    {
        $request = new Request();
        
        if (self::isHtmxRequest($request)) {
            return view($contentView, $data)->render();
        }
        
        $content = view($contentView, $data)->render();
        $layoutData = array_merge($data, ['content' => $content]);
        
        return view($layout, $layoutData)->render();
    }

    public static function setHeaders(Response $response, array $headers = []): Response
    {
        $defaultHeaders = [
            'HX-Push-Url' => 'true',
            'Vary' => 'HX-Request'
        ];

        $allHeaders = array_merge($defaultHeaders, $headers);

        foreach ($allHeaders as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }

    public static function triggerEvent(Response $response, string $eventName, array $data = []): Response
    {
        $response->header('HX-Trigger', json_encode([
            $eventName => $data
        ]));

        return $response;
    }

    public static function redirect(string $url): Response
    {
        $response = Response::make();
        $response->header('HX-Redirect', $url);
        return $response;
    }

    public static function refresh(): Response
    {
        $response = Response::make();
        $response->header('HX-Refresh', 'true');
        return $response;
    }
}

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $data = [
            'title' => 'Home',
            'company' => $this->getCompany(),
        ];

        return Response::make()->view('pages.home', $data);
    }

    public function about(): Response
    {
        $data = [
            'title' => 'About Us',
            'company' => $this->getCompany(),
        ];

        return Response::make()->view('pages.about', $data);
    }

    public function services(): Response
    {
        $data = [
            'title' => 'Our Services',
            'company' => $this->getCompany(),
        ];

        return Response::make()->view('pages.services', $data);
    }
}