<?php

namespace App\Repositories\CategoryAttribute;

use App\Enums\AttributeDataType;
use App\Repositories\BaseRepository;
use App\Models\CategoryAttribute;
use App\Repositories\CategoryAttribute\CategoryAttributeRepositoryInterface;

class CategoryAttributeRepository extends BaseRepository implements CategoryAttributeRepositoryInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * GetModel
     *
     * @return string
     */
    public function getModel()
    {
        return CategoryAttribute::class;
    }

    /**
     * Get List With Pagination
     *
     * @param  mixed $request
     * @return mixed
     */
    public function getListWithPagination($category_id, $request)
    {
        $query = $this->model->with('valueAttributes')->where('category_id', $category_id);

        if ($request->filled('search_name')) {
            $query->where('name', 'like', "%{$request->input('search_name')}%");
        }

        if ($request->filled('sort_created_at')) {
            $query->orderBy('created_at', $request->input('sort_created_at'));
        }

        $limit = $request->filled('limit') ? $request->input('limit') : config('erp.per_page');
        $results = $query->paginate($limit);

        return $results;
    }

    public function createCategoryAttribute($data, $category_id)
    {
        $data['category_id'] = $category_id;
        $categoryAttribute = $this->model->create($data);
        // Store value attributes when datatype is select box
        if ($categoryAttribute->data_type === AttributeDataType::SELECT_BOX->value) {
            $categoryAttribute->valueAttributes()->createMany($data['value_attributes']);
        }
    }
}
