<?php

namespace App\Services\Products;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\CategoryAttribute\CategoryAttributeRepositoryInterface;

class CategoryAttributeService
{
    private $categoryRepository;
    private $categoryAttributeRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository, CategoryAttributeRepositoryInterface $categoryAttributeRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryAttributeRepository = $categoryAttributeRepository;
    }

    /**
     * Get List CategoryAttribute with Pagination
     *
     * @param  mixed $category_id
     * @param  mixed $request
     * @return mixed
     */
    public function getListCategoryAttribute($category_id, $request): mixed
    {
        return $this->categoryAttributeRepository->getListWithPagination($category_id, $request);
    }

    /**
     * Create a CategoryAttribute
     *
     * @param  mixed $category_id
     * @param  mixed $request
     * @return mixed
     */
    public function createCategoryAttribute($request, $category_id): mixed
    {
        $category = $this->categoryRepository->find($category_id);
        if (empty($category)) {
            return false;
        }
        $data = $request->all();
        if (!empty($data['value_attributes'])) {
            $value_attributes = [];
            foreach ($data['value_attributes'] as $attribute) {
                $value_attributes[] = ['value' => $attribute];
            }
            $data['value_attributes'] = $value_attributes;
        }
        return $this->categoryAttributeRepository->createCategoryAttribute($data, $category_id);
    }

    /**
     * Show a CategoryAttribute
     *
     * @param  mixed $category_id
     * @param  mixed $id
     * @return mixed
     */
    public function showCategoryAttribute($category_id, $id): mixed
    {
        return $this->categoryAttributeRepository->getFirstBy(['category_id' => $category_id, 'id' => $id], [], ['valueAttributes']);
    }

    /**
     * Update a CategoryAttribute
     *
     * @param  mixed $category_id
     * @param  mixed $id
     * @param  mixed $request
     * @return mixed
     */
    public function updateCategoryAttribute($category_id, $id, $request): mixed
    {
        $categoryAttribute = $this->categoryAttributeRepository->getFirstBy(['category_id' => $category_id, 'id' => $id]);
        if (empty($categoryAttribute)) {
            return $categoryAttribute;
        }

        $valueAttributes = $request->get('value_attributes');
        // Update Category attribute
        $categoryAttribute->update($request->all());
        
        // Delete attribute value
        $categoryAttribute->valueAttributes()->delete();

        // Insert new attribute value
        if (!empty($valueAttributes)) {
            $categoryAttribute->valueAttributes()->createMany($valueAttributes);
        }
        return $categoryAttribute;
    }

    /**
     * Delete CategoryAttribute by Id
     *
     * @param  mixed $id
     * @return mixed
     */
    public function deleteCategoryAttribute($id): mixed
    {
        return $this->categoryAttributeRepository->delete($id);
    }
}
