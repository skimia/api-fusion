<?php

namespace Skimia\ApiFusion\Http\Controllers\Api;

use Illuminate\Database\Eloquent\MassAssignmentException;
use Input;
use League\Fractal\TransformerAbstract;
use App\Domain\ResourceService;
use    Dingo\Api\Exception\StoreResourceFailedException;
use    Dingo\Api\Exception\UpdateResourceFailedException;
use    Dingo\Api\Exception\DeleteResourceFailedException;
use App\Domain\Exceptions\DomainException;
use App\Domain\Exceptions\ValidationException;

abstract class ResourceServiceController extends ApiController
{
    /**
     * Transformer class to use when presenting the resource data to the user.
     * @var object TransformerAbstract
     */
    protected $transformer;
    /**
     * Service class to use when working with the resource.
     * @var object ResourceService
     */
    protected $service;

    /**
     * Protect and filter all Resources controllers.
     */
    public function __construct()
    {
        $this->requireAuth(['store', 'update', 'destroy']); // require API auth for these methods
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = $this->service->all();

        return $this->response()->collection($items, $this->transformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $item = $this->service->single($id);

        return $this->response()->item($item, $this->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        try {
            $this->service->store(Input::get());

            return $this->response()->created();
        } catch (ValidationException $e) {
            throw new StoreResourceFailedException('Store failed', $e->getValidationErrors());
        } catch (DomainException $e) {
            throw new StoreResourceFailedException('Store failed', [$e->getMessage()]);
        } catch (MassAssignmentException $e) {
            throw new StoreResourceFailedException('Store failed : Invalid model Configuration ($fillable)', [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update()
    {
        try {
            $ids = func_get_args();
            $id = end($ids);
            $this->service->update($id, Input::get());

            return $this->response()->noContent();
        } catch (ValidationException $e) {
            throw new UpdateResourceFailedException('Update failed', $e->getValidationErrors());
        } catch (DomainException $e) {
            throw new UpdateResourceFailedException('Update failed', [$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy()
    {
        try {
            $ids = func_get_args();
            $id = end($ids);
            $this->service->destroy($id);

            return $this->response()->noContent();
        } catch (ValidationException $e) {
            throw new DeleteResourceFailedException('Delete failed', $e->getValidationErrors());
        } catch (DomainException $e) {
            throw new DeleteResourceFailedException('Delete failed', [$e->getMessage()]);
        }
    }
}
