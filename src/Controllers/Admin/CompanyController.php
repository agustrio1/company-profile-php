<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Services\CompanyService;
use App\Validators\CompanyValidator;

class CompanyController
{
    private CompanyService $companyService;
    private CompanyValidator $validator;

    public function __construct()
    {
        $this->companyService = new CompanyService();
        $this->validator = new CompanyValidator();
    }

    public function edit(Request $request): Response
    {
        $company = $this->companyService->get();

        return Response::make()->view('admin.company.edit', [
            'company' => $company
        ]);
    }

    public function update(Request $request): Response
    {
        $data = $request->except(['_csrf_token', '_method']);

        if (!$this->validator->validateCompany($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $logo = $request->file('logo');
        
        $company = $this->companyService->createOrUpdate($data, $logo);

        if (!$company) {
            return Response::make()
                ->with('error', 'Failed to save company profile')
                ->back();
        }

        return Response::make()
            ->with('success', 'Company profile saved successfully')
            ->back();
    }
}