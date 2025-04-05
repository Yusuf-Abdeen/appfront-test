@extends('layouts.admin')

@section('title', 'Admin - Products')

@section('header', 'Manage Products')

@section('header-buttons')
<a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add New Product</a>
@endsection

@section('styles')
<style>
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .admin-table th, .admin-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .admin-table th {
        background-color: #f2f2f2;
    }
    .admin-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .product-thumbnail {
        max-width: 50px;
        max-height: 50px;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" class="product-thumbnail" alt="{{ $product->name }}">
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->formatted_price }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection