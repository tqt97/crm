<?php

namespace App\Repositories\Category;

use App\Repositories\BaseRepository;
use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
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
        return Category::class;
    }
    
    /**
     * Get Attribute With CategoryId
     *
     * @param  mixed $id
     * @return mixed
     */
    public function getAttributeWithCategoryId(int $id): mixed
    {
        return Category::with(
            [
                'categoryAttributes.valueAttributes'
            ]
        )->where('categories.id', $id)->get();
    }

    /**
     * Get List With Pagination
     *
     * @param  mixed $request
     * @return mixed
     */
    public function getListWithPagination($request)
    {
        $query = $this->model->query();

        if ($request->filled('search_code')) {
            $query->where('code', 'like', "%{$request->input('search_code')}%");
        }

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

    public function getListCategoryForProduct($request)
    {
        
        return Category::with(
            [
                'categoryAttributes.valueAttributes'
            ]
        )->get()->makeHidden(["created_at", "created_by"]);
    }
}
