<?php

namespace App\View\Components;

use App\Models\Post as PostModel;
use Illuminate\View\Component;

class Post extends Component
{
    /**
     * The underlying post data model.
     *
     * @var PostModel
     */
    public $model;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(PostModel $model)
    {
        $this->model = $model;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.post');
    }
}
