<?php

namespace App\Http\Controllers\Api\V1\Assignments;

use Illuminate\Support\Carbon;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\AssignmentStatus;
use App\Enums\ProductStatus;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Assignments\CreateRequest;
use App\Http\Requests\Assignments\UpdateRequest;
use App\Http\Resources\PaginationResource;
use App\Models\Assignment;
use App\Models\Product;

class AssignmentController extends BaseController
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('assignment-list'), only: ['index']),
            new Middleware(PermissionMiddleware::using('assignment-store'), only: ['store']),
            new Middleware(PermissionMiddleware::using('assignment-show'), only: ['show']),
            new Middleware(PermissionMiddleware::using('assignment-update'), only: ['update']),
            new Middleware(PermissionMiddleware::using('assignment-delete'), only: ['destroy']),
        ];
    }

    /**
     * Get a list of Assignments based on the specified conditions in the request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $list = (new Assignment())->getListWithPagination($request);
        $results = new PaginationResource($list);
        return $this->sendResponse(__(config('erp.msg_retrieved')), $results);
    }

    /**
     * Create a new Assignment with the specified data from the request.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRequest $request)
    {
        $insertData = [];
        $assignments = $request->input('assignments');
        $productIds = collect($assignments)->pluck('product_ids')->flatten()->unique()->toArray();
        // Retrieve all products (and their children) in one query
        $products = Product::whereIn('id', $productIds)
            ->with('children') // Get child products
            ->select('id', 'parent_product_id') // Only the necessary columns
            ->get();

        $productIdsToUpdate = [];
        $assignmentData = [
            'status' => AssignmentStatus::ASSIGNED->value,
            'assigned_date' => Carbon::now(),
            'created_by' => auth()->id(), // Get current user id
        ];

        DB::transaction(function () use ($assignmentData, $assignments, $productIdsToUpdate, $products) {
            // Prepare data for insertion
            foreach ($assignments as $assignment) {
                $assignmentData['user_id'] = $assignment['user_id'];
                // Filter out only the relevant products for this assignment
                $assignmentProductIds = $assignment['product_ids'];

                // Iterate through the products them add them to the insert data And add children of the product to InsertData
                foreach ($products as $product) {
                    if (in_array($product->id, $assignmentProductIds)) {
                        $assignmentData['product_id'] = $product->id;
                        $insertData[] = $assignmentData;
                        // Add the matching product ids to the productIdsToUpdate array
                        $productIdsToUpdate[] = $product->id;

                        foreach ($product->children as $child) {
                            $assignmentData['product_id'] = $child->id;
                            $insertData[] = $assignmentData;
                            // Add product child id to productIdsToUpdate array
                            $productIdsToUpdate[] = $child->id;
                        }
                    }
                }
            }

            // Insert all the records into the Assignment table in one batch
            if (!empty($insertData)) {
                // Insert data in batches of 1000 to avoid memory issues for large sets of data
                foreach (array_chunk($insertData, 1000) as $chunk) {
                    Assignment::insert($chunk);
                }
            }

            // Update the status of the products related to the assignments
            if (!empty($productIdsToUpdate)) {
                // Remove duplicates in case of repeated products
                $productIdsToUpdate = array_unique($productIdsToUpdate);

                // Update product status in one query
                Product::whereIn('id', $productIdsToUpdate)
                    ->update(['status' => ProductStatus::ASSIGNED->value]);
            }
        });

        return $this->sendResponse(__(config('erp.msg_created')), $insertData, Response::HTTP_CREATED);
    }

    /**
     * Get the details of a specific assignment.
     *
     * @param Assignment $assignment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Assignment $assignment)
    {
        return $this->sendResponse(__(config('erp.msg_retrieved')), $assignment->load(['product', 'user']));
    }

    /**
     * Update the specified assignment with new data.
     *
     * @param int $id
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $assignments = DB::transaction(function () use ($request) {
            $data = $request->only(['status', 'reason_type', 'description', 'assigned_date', 'returned_date']);
            $data['returned_date'] = Carbon::parse($data['returned_date'])->format('Y-m-d');
            // Update the assignments
            return Assignment::whereIn('id', $request->input('assignments'))->update($data);
        });

        return $this->sendResponse(__(config('erp.msg_updated')), $assignments);
    }
}
