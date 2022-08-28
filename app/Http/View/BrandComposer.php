<?php

namespace App\Http\View;

use Illuminate\View\View;
use App\Brand;

class BrandComposer
{
    public function compose(View $view)
    {
        $brands = Brand::with(['child'])->withCount(['child'])->getParent()->orderBy('name', 'ASC')->get();
        $view->with('brands', $brands);
    }
}