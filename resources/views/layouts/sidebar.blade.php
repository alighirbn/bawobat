<style>
    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #161616;
    }

    .item {
        color: #f8e8cc;
        text-align: center;
        font-weight: bold;
        padding-bottom: 30px;
    }

    .item a.sub-btn {
        display: inline-flex;
        align-items: center;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .item .sub-menu :hover {
        color: yellow;
    }

    .item i {
        margin-right: 10px;
        transition: 0.3s ease;
    }

    .rotate {
        transform: rotate(-180deg);
    }

    .item .sub-menu a {
        padding: 5px;
        font-size: 14px;
    }

    .item .sub-menu a:focus {
        padding: 5px;
        /* Padding for sub-menu items */
        color: #bcbbbb;
        /* Lighter text color */
        transition: background 0.3s;
        /* Smooth transition */
    }

    .sub-menu {
        padding-left: 20px;
        /* Indent sub-menu items */
        background-color: #2c2c4429;
        /* Sub-menu background */
        border-left: 4px solid #ffcc00;
        /* Highlighted border for sub-menu */
    }

    .div {
        font-family: 'Cairo', sans-serif;
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">

<!-- Boxicons CSS -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<div id="sidebarmenu" class="py-2 " style="display: none">
    <div class="p-2">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="h-6;max-width: auto; height: 150px;">
    </div>
    <div class="item"><a class="sub-btn"> {{ __('word.links') }}<i class="bx bx-chevrons-down dropdown"></i>
        </a>
        <div class="flex flex-col sub-menu" style="display: none;">
            <a href="https://yasmin.server.local/ar/dashboard" target="_blank">مجمع واحة الياسمين</a>
        </div>

    </div>
    @can('expense-list')
        <div class="item"><a class="sub-btn"> {{ __('word.accountant') }} <i class="bx bx-chevrons-down dropdown"></i>
            </a>
            <div class="flex flex-col   sub-menu" style="display: none;">
                @include('payment.nav.navigation')
                @include('expense.nav.navigation')
                @include('cash_account.nav.navigation')
                @include('cash_transfer.nav.navigation')
            </div>
        </div>
    @endcan

    @can('customer-list')
        <div class="item"><a class="sub-btn"> {{ __('word.Customer') }} <i class="bx bx-chevrons-down dropdown"></i> </a>
            <div class="flex flex-col   sub-menu" style="display: none;">
                @include('customer.nav.navigation')
            </div>
        </div>
    @endcan

    @can('user-list')
        <div class="item"><a class="sub-btn"> {{ __('word.users') }}<i class="bx bx-chevrons-down dropdown"></i> </a>
            <div class="flex flex-col   sub-menu" style="display: none;">
                @include('user.nav.navigation')
            </div>
        </div>
    @endcan
    @can('role-list')
        <div class="item"><a class="sub-btn"> {{ __('word.roles') }}<i class="bx bx-chevrons-down dropdown"></i> </a>
            <div class="flex flex-col   sub-menu" style="display: none;">
                @include('role.nav.navigation')
            </div>
        </div>
    @endcan
</div>
<script>
    $(document).ready(function() {
        $('.sub-btn').on('click', function() {
            const $subMenu = $(this).next('.sub-menu');
            const isExpanded = $(this).attr('aria-expanded') === 'true';

            $('.sub-menu').slideUp(); // Close all sub-menus
            $('.sub-btn').attr('aria-expanded', 'false'); // Reset aria-expanded

            if (!isExpanded) {
                $subMenu.slideDown();
                $(this).attr('aria-expanded', 'true'); // Update current state
            }
            $('.dropdown').removeClass('rotate'); // Reset all rotations
            $(this).find('.dropdown').toggleClass('rotate'); // Rotate current icon
        });
    });
</script>
