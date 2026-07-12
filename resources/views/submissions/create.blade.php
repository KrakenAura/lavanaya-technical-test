@extends('layouts.app')

@section('page-title', 'Create Submission')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <p class="text-white text-sm mb-1 opacity-8">
            Submission
        </p>

        <h4 class="text-white font-weight-bolder mb-0">
            Create Submission
        </h4>
    </div>

    <div class="mt-3 mt-md-0">
        <a
            href="{{ route('web.submissions.index') }}"
            class="btn btn-outline-light mb-0">
            <i class="fas fa-arrow-left me-2"></i>
            Back
        </a>
    </div>
</div>


@if ($errors->any())
<div class="alert alert-danger text-white" role="alert">
    <strong>Please check the form.</strong>

    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
        <li class="text-sm">
            {{ $error }}
        </li>
        @endforeach
    </ul>
</div>
@endif


<form
    method="POST"
    action="{{ route('web.submissions.store') }}"
    enctype="multipart/form-data">
    @csrf

    <div class="row g-4">

        {{-- Main form --}}
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">
                        Submission Information
                    </h6>

                    <p class="text-sm text-secondary mb-0 mt-1">
                        Complete the expense information below.
                    </p>
                </div>

                <div class="card-body">

                    {{-- Title --}}
                    <div class="mb-3">
                        <label
                            for="title"
                            class="form-label">
                            Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="Example: Business trip expense"
                            required>

                        @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>


                    <div class="row">

                        {{-- Category --}}
                        <div class="col-md-6 mb-3">
                            <label
                                for="category_id"
                                class="form-label">
                                Category
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="category_id"
                                name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                required>
                                <option value="">
                                    Select category
                                </option>

                                @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(
                                    old('category_id')==$category->id
                                    )
                                    >
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>

                            @error('category_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>


                        {{-- Amount --}}
                        <div class="col-md-6 mb-3">
                            <label
                                for="amount"
                                class="form-label">
                                Amount
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    Rp
                                </span>

                                <input
                                    type="number"
                                    id="amount"
                                    name="amount"
                                    value="{{ old('amount') }}"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    min="1"
                                    step="1"
                                    placeholder="2500000"
                                    required>

                                @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="mb-3">
                        <label
                            for="description"
                            class="form-label">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Describe the expense purpose...">{{ old('description') }}</textarea>

                        @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>


                    {{-- PO --}}
                    <div class="form-check form-switch">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="is_po"
                            name="is_po"
                            value="1"
                            @checked(old('is_po'))>

                        <label
                            class="form-check-label"
                            for="is_po">
                            This submission is a Purchase Order
                        </label>
                    </div>

                </div>
            </div>
        </div>


        {{-- Attachment and actions --}}
        <div class="col-xl-4">

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">
                        Attachment
                    </h6>

                    <p class="text-sm text-secondary mb-0 mt-1">
                        Upload supporting document.
                    </p>
                </div>

                <div class="card-body">
                    <label
                        for="attachment"
                        class="form-label">
                        File
                    </label>

                    <input
                        type="file"
                        id="attachment"
                        name="attachment"
                        class="form-control @error('attachment') is-invalid @enderror"
                        accept=".pdf,.jpg,.jpeg,.png">

                    @error('attachment')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                    <p class="text-xs text-secondary mt-2 mb-0">
                        PDF, JPG, JPEG, or PNG. Maximum 2 MB.
                    </p>
                </div>
            </div>


            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">
                        Save Submission
                    </h6>
                </div>

                <div class="card-body">
                    <p class="text-sm text-secondary">
                        The submission will be saved as a draft.
                        You can review it before submitting it for approval.
                    </p>

                    <button
                        type="submit"
                        class="btn bg-gradient-primary w-100 mb-2">
                        <i class="fas fa-save me-2"></i>
                        Save Draft
                    </button>

                    <a
                        href="{{ route('web.submissions.index') }}"
                        class="btn btn-outline-secondary w-100 mb-0">
                        Cancel
                    </a>
                </div>
            </div>

        </div>

    </div>
</form>

@endsection
