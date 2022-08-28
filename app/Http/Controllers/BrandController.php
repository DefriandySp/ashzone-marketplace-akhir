<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;
use Illuminate\Support\Facades\Gate;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brand = Brand::with(['parent'])->orderBy('created_at', 'ASC')->paginate(10);
        $parent = Brand::getParent()->orderBy('name', 'ASC')->get();
        return view('brands.index', compact('brand', 'parent'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:brands'
        ]);

        $request->request->add(['slug' => $request->name]);
        Brand::create($request->except('_token'));
        return redirect(route('brand.index'))->with(['success' => 'Kategori Baru Ditambahkan!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand = Brand::find($id); 
        $parent = Brand::getParent()->orderBy('name', 'ASC')->get(); 
      
        return view('brands.edit', compact('brand', 'parent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:brands,name,' . $id
        ]);

        $brand = Brand::find($id); 
        $brand->update([
            'name' => $request->name,
            'slug' => $request->name,
            'parent_id' => $request->parent_id
        ]);

        return redirect(route('brand.index'))->with(['success' => 'Kategori Diperbaharui!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //FUNGSI INI AKAN MEMBENTUK FIELD BARU YANG BERNAMA child_count dan product_count
        $brand = Brand::withCount(['child', 'product'])->find($id);
        if ($brand->child_count == 0 && $brand->product_count == 0) {
            $brand->delete();
            return redirect(route('brand.index'))->with(['success' => 'Kategori Dihapus!']);
        }
        return redirect(route('brand.index'))->with(['error' => 'Kategori Ini Memiliki Anak Kategori atau Produk!']);
    }
}
