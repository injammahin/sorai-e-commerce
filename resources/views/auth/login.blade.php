@extends('layouts.store')

@section(
    'title',
    ($isAdminLogin ?? false)
        ? 'Admin Sign In | AATCHALA'
        : 'Sign In | AATCHALA'
)

@section('content')

<div class="auth-shell">

    <div class="auth-card !max-w-[500px]">

        <div class="text-center">

            <div
                class="
                    mx-auto
                    grid
                    h-14
                    w-14
                    place-items-center
                    rounded-full
                    bg-[#f2ede6]
                    text-[#a84f27]
                "
            >

                <i class="fa-regular fa-user text-xl"></i>

            </div>


            <p class="eyebrow mt-5">

                {{
                    ($isAdminLogin ?? false)
                        ? 'AATCHALA administration'
                        : 'Welcome back'
                }}

            </p>


            <h1 class="mt-2 text-4xl sm:text-5xl">

                {{
                    ($isAdminLogin ?? false)
                        ? 'Admin sign in'
                        : 'Sign in'
                }}

            </h1>

        </div>



        {{-- =====================================================
            CUSTOMER GOOGLE LOGIN
        ====================================================== --}}
        @if(!($isAdminLogin ?? false))

            <a
                class="btn btn-outline mt-8 w-full"
                href="{{ route('google.redirect') }}"
            >

                <i class="fa-brands fa-google text-base"></i>

                Continue with Google

            </a>


            <div class="my-6 flex items-center gap-3">

                <span class="h-px flex-1 bg-gray-200"></span>

                <small
                    class="
                        muted
                        uppercase
                        tracking-[.18em]
                    "
                >
                    or email
                </small>

                <span class="h-px flex-1 bg-gray-200"></span>

            </div>

        @else

            <div
                class="
                    mt-7
                    rounded-sm
                    border
                    border-[#e5ded5]
                    bg-[#faf8f5]
                    p-4
                    text-xs
                    leading-5
                    text-gray-600
                "
            >
                Administrator access uses email and password only.
            </div>

        @endif



        <form
            method="POST"
            action="{{ route('login') }}"
            class="{{
                ($isAdminLogin ?? false)
                    ? 'mt-7'
                    : ''
            }}"
        >

            @csrf



            {{-- EMAIL --}}
            <label>

                <span class="label">
                    Email
                </span>


                <input
                    class="field"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                    autofocus
                >


                @error('email')

                    <span class="error block">
                        {{ $message }}
                    </span>

                @enderror

            </label>



            {{-- PASSWORD --}}
            <label class="mt-5 block">

                <span class="label">
                    Password
                </span>


                <div class="relative">

                    <input
                        id="login-password"
                        class="field !pr-10"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        required
                    >


                    <button
                        type="button"
                        id="toggle-login-password"
                        class="
                            absolute
                            inset-y-0
                            right-0
                            grid
                            w-10
                            place-items-center
                            text-gray-400

                            hover:text-black
                        "
                        aria-label="Show password"
                    >

                        <i class="fa-regular fa-eye"></i>

                    </button>

                </div>


                @error('password')

                    <span class="error block">
                        {{ $message }}
                    </span>

                @enderror

            </label>



            <div
                class="
                    mt-5
                    flex
                    items-center
                    justify-between
                    gap-4
                    text-xs
                "
            >

                <label
                    class="
                        flex
                        cursor-pointer
                        items-center
                        gap-2
                    "
                >

                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                        @checked(old('remember'))
                    >

                    Remember me

                </label>


                <a
                    class="underline"
                    href="{{ route('password.request') }}"
                >
                    Forgot password?
                </a>

            </div>



            <button
                type="submit"
                class="btn btn-dark mt-7 w-full"
            >
                Sign in
            </button>

        </form>



        @if(!($isAdminLogin ?? false))

            <p class="mt-6 text-center text-sm">

                New to AATCHALA?

                <a
                    class="underline"
                    href="{{ route('register') }}"
                >
                    Create an account
                </a>

            </p>

        @endif

    </div>

</div>

@endsection


@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const button =
            document.getElementById(
                'toggle-login-password'
            );


        const input =
            document.getElementById(
                'login-password'
            );


        button?.addEventListener(
            'click',
            function () {

                if (!input) {
                    return;
                }


                const showPassword =
                    input.type
                    ===
                    'password';


                input.type =
                    showPassword
                        ? 'text'
                        : 'password';


                const icon =
                    button.querySelector(
                        'i'
                    );


                icon?.classList.toggle(
                    'fa-eye',
                    !showPassword
                );


                icon?.classList.toggle(
                    'fa-eye-slash',
                    showPassword
                );


                button.setAttribute(
                    'aria-label',

                    showPassword
                        ? 'Hide password'
                        : 'Show password'
                );

            }
        );

    }
);

</script>

@endpush