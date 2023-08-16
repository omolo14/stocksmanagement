@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Products</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                                <li class="breadcrumb-item active">Create</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if(session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <h4 class="card-title">Add New Product Details</h4>
                            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" name="name" id="name" required>
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <label>Select Category:</label>
                                    <select id="category_id" name="category_id" class="form-control">
                                        <option value="">Select the category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="form-group">
                                    <label>Select Category:</label>
                                    <select id="category_id" name="category_id" class="form-control">
                                        <option value="">Select the category</option>
                                        @foreach($categories as $categoryId => $categoryName)
                                            <option value="{{ $categoryId }}">{{ $categoryName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <br>

                                <div class="mb-3">
                                    <label>Description</label>
                                    <div>
                                        <textarea class="form-control" rows="5" type="text" name="description" id="description" required></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Buying Price</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="number" name="buyingprice" id="buyingprice" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Selling Price</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="number" name="sellingprice" id="sellingprice" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Stock Quantity</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="number" name="stockquantity" id="stockquantity" required>
                                    </div>
                                </div>

                                <div>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                    <a class="btn btn-secondary" href="{{ url()->previous() }}">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> © Upcube.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://1.envato.market/themesdesign" target="_blank">Themesdesign</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
<!-- end main content-->
@endsection
