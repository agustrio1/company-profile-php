<?php

namespace App\Controllers;

use App\Core\Response;
use App\Services\TeamService;

class TeamController
{
    private TeamService $teamService;

    public function __construct()
    {
        $this->teamService = new TeamService();
    }

    public function index(): Response
    {
        $data = [
            'teams' => $this->teamService->getAllWithEmployees()
        ];

        return Response::make()->view('pages.team.index', $data);
    }
}