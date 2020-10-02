<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Blog\Admin\BaseController;
use App\Repositories\BlogCategoryRepository;

class CategoryController extends BaseController
{
    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository;
    private $forComboBox;

    function __construct()
    {
        // parent::__construct();
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $paginator = BlogCategory::paginate(5);
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);

        return view('blog.admin.categories.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new BlogCategory();
        // $categoryList = BlogCategory::all();
        $this->forComboBox = $this->blogCategoryRepository->getForComboBox();
        $categoryList = $this->forComboBox;

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        /*
        // В обсервер
        if (empty($data['slug'])) {
            $data['slug'] = str_slug($data['title']);
        }
        */

        // Новый объект для вставки в БД
        $item = new BlogCategory($data);
        $item->save();

        if ($item->exists) {
            return redirect()->route('blog.admin.categories.edit', [$item->id]);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, BlogCategoryRepository $categoryRepository)
    {
        // $item = BlogCategory::find($id);
        // $item = BlogCategory::where('id', $id)->first();
        // $item = BlogCategory::findOrFail($id); // 404 page
        // $categoryList = BlogCategory::all();
        $item = $categoryRepository->getEdit($id);
        // $item = $this->blogCategoryRepository->getEdit($id);

        // $v['title_before']      = $item->title;
        // $item->title = 'Мой заголовок КАПСЛОКОМ 123   .   ';
        // $v['title_after']       = $item->title;
        // $v['getAttribute']      = $item->getAttribute('title');
        // $v['attributesArray']   = $item->attributesToArray();
        // $v['attributes']        = $item->attributes['title'];
        // $v['getAttributeValue'] = $item->getAttributeValue('title');
        // $v['getMutatedAttributes'] = $item->getMutatedAttributes();
        // $v['hasGetMutator(title)'] = $item->hasGetMutator('title');
        // $v['toArray']           = $item->toArray();
        // dd($v, $item);


        if (empty($item)) {
            abort(404);
        }
        $categoryList = $categoryRepository->getForComboBox();

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        /*
        $rules = [
            'title'         => 'required|min:5|max:200',
            'slug'          => 'max:200',
            'description'   => 'string|min:3|max:500',
            'parent_id'     => 'required|integer|exists:blog_categories,id',
        ];
        // 1.
         $validated = $this->validate($request, $rules);
        // 2.
        // $validated = $request->validate($rules);
        // 3.
        // $validator = \Validator::make($request->all(), $rules);
        // $validated[] = $validator->passes();
        // $validated[] = $validator->validate();
        // $validated[] = $validator->valid();
        // $validated[] = $validator->failed();
        // $validated[] = $validator->errors();
        // $validated[] = $validator->fails();
        */

        $item = BlogCategory::find($id);
        $item = $this->blogCategoryRepository->getEdit($id);
        // dd($item);
        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запись #{$id} не найдена"])
                ->withInput();
        }

        $data = $request->all();

        /*
        // В обсервер
        if (empty($data['slug'])) {
            $data['slug'] = str_slug($data['title']);
        }
        */

        $result = $item->fill($data)->save();
        // $result = $item->update($data); // same to $item->fill($data)->save();

        if ($result) {
            return redirect()->route('blog.admin.categories.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
                ->withInput();
        }
    }
}
