<?php

namespace App\Repositories\Products;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use App\Models\Product;
use App\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    use HasAttributes;
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return Product::class;
    }

    /**
     * Get data product by Id
     *
     * @param  mixed $id
     * @return mixed
     */
    public function getProductById(int $id)
    {
        return $this->find($id);
    }

    /**
     * Get List With Pagination
     *
     * @param  mixed $request
     * @return mixed
     */
    public function  getListWithPagination($request)
    {
        $query = $this->model->with(['category', 'parent'])->sort($request)->applyLimit($request);

        return $query;
    }
}
