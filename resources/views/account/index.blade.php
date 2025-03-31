<x-app-layout>
    <x-slot name="header">
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
                <div class="container">

                    <!-- Loop through all accounts -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('word.add') }}</th>

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
                                        <div class="flex">
                                            @can('account-create')
                                                <button type="button" class="my-1 mx-1 btn btn-custom-show"
                                                    data-bs-toggle="modal" data-bs-target="#addAccountModal"
                                                    data-parent-id="{{ $account->id }}"
                                                    data-parent-code="{{ $account->code }}">
                                                    +
                                                </button>
                                            @endcan

                                            <button type="button" class="btn btn-custom-edit" data-bs-toggle="modal"
                                                data-bs-target="#editAccountModal" data-id="{{ $account->id }}"
                                                data-name="{{ $account->name }}" data-code="{{ $account->code }}">
                                                {{ __('word.edit') }}
                                            </button>
                                        </div>
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
                                                        <button type="button" class="btn btn-custom-edit"
                                                            data-bs-toggle="modal" data-bs-target="#editAccountModal"
                                                            data-id="{{ $child->id }}"
                                                            data-name="{{ $child->name }}"
                                                            data-code="{{ $child->code }}">
                                                            {{ __('word.edit') }}
                                                        </button>
                                                        <form action="{{ route('account.destroy', $child->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-custom-delete"
                                                                onclick="return confirm('{{ __('word.delete_confirm') }}')">
                                                                {{ __('word.delete') }}
                                                            </button>
                                                        </form>

                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                        @endif
                                    </td>
                                    <td>{{ __('word.' . $account->type) }}</td>
                                    <td>{{ $account->class }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Add Account Modal -->
    <div class="modal fade text-gray-900" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('word.account_add') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('account.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label class="form-label">{{ __('word.name') }}</label>
                        <input type="text" class="form-control" name="name" required>

                        <label class="form-label">{{ __('word.parent_code') }}</label>
                        <input type="text" class="form-control" id="parent_code" readonly>

                        <label class="form-label">{{ __('word.last_digits') }}</label>
                        <input type="number" class="form-control" id="last_digits" name="last_digits" required>

                        <label class="form-label">{{ __('word.full_code') }}</label>
                        <input type="text" class="form-control" id="code" name="code" readonly>

                        <input type="hidden" id="parent_id" name="parent_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('word.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('word.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Account Modal -->
    <div class="modal fade text-gray-900" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('word.account_edit') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editAccountForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <label class="form-label">{{ __('word.name') }}</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>

                        <label class="form-label">{{ __('word.full_code') }}</label>
                        <input type="text" class="form-control" id="edit_code" name="code" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('word.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('word.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var addAccountModal = document.getElementById('addAccountModal');
            var parentIdInput = addAccountModal.querySelector('#parent_id');
            var parentCodeInput = addAccountModal.querySelector('#parent_code');
            var lastDigitsInput = addAccountModal.querySelector('#last_digits');
            var fullCodeInput = addAccountModal.querySelector('#code');

            addAccountModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var parentId = button.getAttribute('data-parent-id');
                var parentCode = button.getAttribute('data-parent-code');

                parentIdInput.value = parentId;
                parentCodeInput.value = parentCode;
                fullCodeInput.value = '';
                lastDigitsInput.value = '';
            });

            lastDigitsInput.addEventListener('input', function() {
                fullCodeInput.value = parentCodeInput.value + lastDigitsInput.value;
            });

            // Edit Modal
            var editAccountModal = document.getElementById('editAccountModal');
            var editNameInput = editAccountModal.querySelector('#edit_name');
            var editCodeInput = editAccountModal.querySelector('#edit_code');
            var editForm = document.getElementById('editAccountForm');

            editAccountModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var code = button.getAttribute('data-code');

                editNameInput.value = name;
                editCodeInput.value = code;
                editForm.action = "/account/" + id;
            });
        });
    </script>
</x-app-layout>
