<?php

namespace App\Http\Controllers\Video;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\IndexModuleRequest;
use App\Services\ModuleService;
use App\Services\YoutubeService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Inertia\Inertia;

class YoutubeController extends Controller
{
    /**
     * @brief   The module service instance.
     */
    protected ModuleService $moduleService;
    protected YoutubeService $youtubeService;

    /**
     * @brief   Constructor to define the resource access (viewing).
     *          Injection of ModuleService dependency.
     */
    public function __construct(ModuleService $moduleService, YoutubeService $youtubeService)
    {
        $this->moduleService = $moduleService;
        $this->youtubeService = $youtubeService;
    }

    /**
     * @brief   Query for the modules with search and ordering + sending props for modal opening.
     *
     * @param   \App\Http\Requests\Module\IndexModuleRequest $request
     *
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function index(IndexModuleRequest $request): SymfonyResponse
    {
        try {
            $companyId = $request->user()->company_id;

            $validated = $request->validated();

            $search   = $validated['q']        ?? '';
            $order    = $validated['order']    ?? 'asc';
            $orderBy  = $validated['orderBy']  ?? 'created_at';
            $status   = $validated['status']   ?? 'all';
            $perPage  = 12;

            $result = $this->moduleService->listModules(
                $companyId,
                $search,
                $orderBy,
                $order,
                $status,
                $perPage
            );

            $paginator = $result['paginator'];

            if ($request->page && $paginator->lastPage() > 0 && $request->page > $paginator->lastPage()) {
                return redirect()->route('modules.index', array_merge(
                    $request->except('page'),
                    ['page' => 1]
                ))->with('warning', __('pagination.invalid_page', [
                    'page'     => $request->page,
                    'lastPage' => $paginator->lastPage(),
                ]));
            }

            return Inertia::render('modules/Modules', [
                'modules' => $paginator,
                'q'       => $search,
                'order'   => $order,
                'orderBy' => $orderBy,
                'status'  => $status,
                'counts'  => $result['counts'],
                'modal'   => 'VideoGenerateModal'
            ])->with('success', 'general.success')
                ->toResponse($request)
                ->setStatusCode(SymfonyResponse::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error showing module', [
                'user_id'   => Auth::id(),
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            return Inertia::render('modules/Modules')
                ->with('errors', 'errors.internal-server-error')
                ->toResponse($request)
                ->setStatusCode(SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store()
    {
        try {
            $result = $this->youtubeService->getChannel();
            dd($result);
        } catch (Exception $e) {
            dd($e);
        }
    }
}
