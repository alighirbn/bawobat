<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <div class="flex justify-start">
            @include('account.nav.navigation')
            @include('costcenter.nav.navigation')
            @include('period.nav.navigation')
            @include('opening_balance.nav.navigation')
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <!-- Loop through all accounts -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('word.add') }}</th>
                                <th>{{ __('word.soa') }}</th>
                                <th>{{ __('word.code') }}</th>
                                <th>{{ __('word.catogery') }}</th>
                                <th>{{ __('word.name') }}</th>
                                <th>{{ __('word.type') }}</th>
                                <th>{{ __('word.class') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr>
                                    <!-- Action Button -->
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#addAccountModal" data-parent-id="{{ $account->id }}">
                                            +
                                        </button>
                                    </td>

                                    <!-- Account Details -->
                                    <td>{{ $account->code }}</td>
                                    <td>{{ $account->name }}</td>
                                    <td>
                                        @if ($account->children->isNotEmpty())
                                            <ul class="list-group">
                                                @foreach ($account->children as $child)
                                                    <li class="list-group-item">
                                                        {{ $child->name . ' ( ' . $child->code . ' )' }}

                                                        <a href="{{ route('report.statement_of_account', [
                                                            'account_id' => $child->id,
                                                            'start_date' => $startDate,
                                                            'end_date' => $endDate,
                                                        ]) }}"
                                                            class="btn btn-info btn-sm">
                                                            {{ __('word.soa') }}
                                                        </a>

                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                        @endif
                                    </td>
                                    <td>{{ __('word.' . $account->type) }}</td>
                                    <td>{{ $account->class }}</td>

                                    <!-- Child Accounts -->

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
    <!-- Modal for adding a new account -->
    <div class="modal fade  text-gray-900" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccountModalLabel">{{ __('word.account_add') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('account.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('word.name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <!-- Hidden field to pass the parent account ID -->
                        <input type="hidden" id="parent_id" name="parent_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-custom-show"
                            data-bs-dismiss="modal">{{ __('word.close') }}</button>
                        <button type="submit" class="btn btn-custom-add">{{ __('word.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Set the parent_id in the modal when the "Add Child Account" button is clicked
        var addAccountModal = document.getElementById('addAccountModal');
        addAccountModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var parentId = button.getAttribute('data-parent-id'); // Get parent_id from button

            // Set the parent_id field in the modal form
            var parentIdInput = addAccountModal.querySelector('#parent_id');
            parentIdInput.value = parentId;
        });
    </script>
</x-app-layout>
