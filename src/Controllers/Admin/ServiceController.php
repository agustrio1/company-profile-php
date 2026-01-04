<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Services\CompanyServiceService;
use App\Validators\ServiceValidator;

class ServiceController
{
    private CompanyServiceService $serviceService;
    private ServiceValidator $validator;

    public function __construct()
    {
        $this->serviceService = new CompanyServiceService();
        $this->validator = new ServiceValidator();
    }

    public function index(Request $request): Response
    {
        return Response::make()->view('admin.services.index');
    }

    public function table(Request $request): Response
    {
        $search = $request->query('search');
        $page = (int)$request->query('page', 1);
        
        $result = $search 
            ? $this->serviceService->search($search, $page, 50)
            : $this->serviceService->getAll($page, 50);
        
        return Response::make()->view('admin.services._table', [
            'services' => $result['data'],
            'search' => $search
        ], false);
    }

    public function create(Request $request): Response
    {
        return Response::make()->view('admin.services.create');
    }

    public function store(Request $request): Response
    {
        $data = $request->except(['_csrf_token', '_method']);

        if (!$this->validator->validateCreate($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $icon = $request->file('icon');
        $image = $request->file('image');

        $service = $this->serviceService->create($data, $icon, $image);

        if (!$service) {
            return Response::make()
                ->with('error', 'Failed to create service')
                ->back();
        }

        return Response::make()
            ->with('success', 'Service created successfully')
            ->redirect(url('admin/services'));
    }

    public function edit(Request $request, string $id): Response
    {
        $service = $this->serviceService->getById($id);

        if (!$service) {
            return Response::make()
                ->setStatusCode(404)
                ->setContent('Service not found');
        }

        return Response::make()->view('admin.services.edit', [
            'service' => $service
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $data = $request->except(['_csrf_token', '_method']);

        if (!$this->validator->validateUpdate($data)) {
            return Response::make()
                ->withErrors($this->validator->getErrors())
                ->withInput()
                ->back();
        }

        $icon = $request->file('icon');
        $image = $request->file('image');

        $service = $this->serviceService->update($id, $data, $icon, $image);

        if (!$service) {
            return Response::make()
                ->with('error', 'Failed to update service')
                ->back();
        }

        return Response::make()
            ->with('success', 'Service updated successfully')
            ->redirect(url('admin/services'));
    }

    public function confirmDelete(Request $request, string $id): Response
    {
        $service = $this->serviceService->getById($id);
        
        if (!$service) {
            return Response::make()
                ->setStatusCode(404)
                ->setContent('Service tidak ditemukan');
        }
        
        return Response::make()->view('admin.services._delete_modal', ['service' => $service], false);
    }

    public function destroy(Request $request, string $id): Response
    {
        $isAjax = $request->header('Accept') === 'application/json' || 
                  $request->header('X-Requested-With') === 'XMLHttpRequest';
        $isHtmx = $request->header('HX-Request') !== null;
        
        $deleted = $this->serviceService->delete($id);
        
        if ($isHtmx) {
            if (!$deleted) {
                return Response::make()
                    ->setStatusCode(400)
                    ->setContent($this->htmxScript('Gagal menghapus service', 'danger'));
            }
            
            header('HX-Trigger: serviceDeleted');
            return Response::make()->setContent($this->htmxScript('Service berhasil dihapus', 'success'));
        }
        
        if ($isAjax) {
            return Response::make()->json([
                'success' => $deleted,
                'message' => $deleted ? 'Service berhasil dihapus' : 'Gagal menghapus service'
            ], $deleted ? 200 : 500);
        }
        
        return Response::make()
            ->with($deleted ? 'success' : 'error', $deleted ? 'Service deleted successfully' : 'Failed to delete service')
            ->redirect($deleted ? url('admin/services') : null)
            ->back();
    }
    
    private function htmxScript(string $message, string $type): string
    {
        return '<script>
            document.getElementById("delete-modal").innerHTML = "";
            showToast("' . addslashes($message) . '", "' . $type . '");
        </script>';
    }
}