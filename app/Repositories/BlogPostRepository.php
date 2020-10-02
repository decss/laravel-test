<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogPostRepository extends CoreRepository
{
    /**
     * Получить модель для редактирования в админке
     *
     * @param int $id
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Поулчить список категорий для вывода в селекте
     *
     * @return Collection
     */
    public function getForComboBox()
    {
        // $columns = implode(', ', [
        //     'id',
        //     'CONCAT (id, ". ", title) AS title',
        // ]);
        // $result = $this->startConditions()
        //     ->selectRaw($columns)
        //     ->toBase()
        //     ->get();
        //
        // return $result;
    }

    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate()
    {
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id',
        ];
        $result = $this->startConditions()
            ->select($columns)
            ->orderBy('id', 'DESC')
            // ->with(['category', 'user',])
            ->with([
                'category' => function($query) {
                    $query->select(['id', 'title'])->withTrashed();
                },
                'user:id,name',
            ])

            ->paginate(10);

        return $result;
    }
}
