<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Services\ProductService;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    protected ProductService $productService;
    protected ImageUploadService $imageUploadService;

    public function __construct(ProductService $productService, ImageUploadService $imageUploadService)
    {
        $this->productService = $productService;
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = $this->productService->getAllProducts();
        return view('admin.products', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.add_product');
    }

    /**
     * Store a newly created product in storage.
     *
     * @param ProductStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductStoreRequest $request)
    {
        $data = $request->validated();

        $product = $this->productService->createProduct($data);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Generate a unique name with the original extension
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $product->image = 'uploads/' . $filename;
            $product->save();
        }

        return redirect()->route('admin.products')
            ->with('success', 'Product added successfully');
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        return view('admin.edit_product', compact('product'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param ProductUpdateRequest $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $data = $request->validated();

        $this->productService->updateProduct($product, $data);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Generate a unique name with the original extension
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $product->image = 'uploads/' . $filename;
            $product->save();
        }

        return redirect()->route('admin.products')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);

        return redirect()->route('admin.products')
            ->with('success', 'Product deleted successfully');
    }
}