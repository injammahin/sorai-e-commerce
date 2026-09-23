@extends('layouts.store')

@section('title', 'Create Account | AATCHALA')

@section('content')

<div class="auth-shell">

    <div class="auth-card !max-w-[520px]">

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
                Join AATCHALA
            </p>


            <h1 class="mt-2 text-4xl sm:text-5xl">
                Create account
            </h1>


            <p
                class="
                    muted
                    mx-auto
                    mt-3
                    max-w-md
                    text-sm
                    leading-6
                "
            >
                Create an account with email and verify your inbox,
                or continue securely with Google.
            </p>

        </div>



        {{-- =====================================================
            GOOGLE
        ====================================================== --}}
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
                or register with email
            </small>

            <span class="h-px flex-1 bg-gray-200"></span>

        </div>



        {{-- =====================================================
            MANUAL REGISTRATION
        ====================================================== --}}
        <form
            method="POST"
            action="{{ route('register') }}"
        >

            @csrf



            {{-- NAME --}}
            <label>

                <span class="label">
                    Full name
                </span>


                <input
                    class="field"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    autocomplete="name"
                    maxlength="191"
                    required
                    autofocus
                >


                @error('name')

                    <span class="error block">
                        {{ $message }}
                    </span>

                @enderror

            </label>



            {{-- EMAIL --}}
            <label class="mt-5 block">

                <span class="label">
                    Email address
                </span>


                <input
                    class="field"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    maxlength="191"
                    required
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
                        id="register-password"
                        class="field !pr-10"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        required
                    >


                    <button
                        type="button"
                        class="
                            js-password-toggle
                            absolute
                            inset-y-0
                            right-0
                            grid
                            w-10
                            place-items-center
                            text-gray-400

                            hover:text-black
                        "
                        data-target="register-password"
                        aria-label="Show password"
                    >

                        <i class="fa-regular fa-eye"></i>

                    </button>

                </div>


                <span
                    class="
                        muted
                        mt-2
                        block
                        text-[11px]
                        leading-5
                    "
                >
                    Use at least 8 characters with uppercase,
                    lowercase and a number.
                </span>


                @error('password')

                    <span class="error block">
                        {{ $message }}
                    </span>

                @enderror

            </label>



            {{-- CONFIRM PASSWORD --}}
            <label class="mt-5 block">

                <span class="label">
                    Confirm password
                </span>


                <div class="relative">

                    <input
                        id="register-password-confirmation"
                        class="field !pr-10"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        required
                    >


                    <button
                        type="button"
                        class="
                            js-password-toggle
                            absolute
                            inset-y-0
                            right-0
                            grid
                            w-10
                            place-items-center
                            text-gray-400

                            hover:text-black
                        "
                        data-target="register-password-confirmation"
                        aria-label="Show password confirmation"
                    >

                        <i class="fa-regular fa-eye"></i>

                    </button>

                </div>

            </label>



            {{-- TERMS --}}
            <label
                class="
                    mt-6
                    flex
                    cursor-pointer
                    items-start
                    gap-3
                    text-xs
                    leading-5
                "
            >

                <input
                    class="mt-1"
                    type="checkbox"
                    name="terms"
                    value="1"
                    @checked(old('terms'))
                    required
                >


                <span>

                    I agree to the

                    <a
                        class="underline"
                        href="{{ route('pages.show', 'terms') }}"
                    >
                        terms
                    </a>

                    and

                    <a
                        class="underline"
                        href="{{ route('pages.show', 'privacy') }}"
                    >
                        privacy policy
                    </a>.

                </span>

            </label>


            @error('terms')

                <span class="error block">
                    {{ $message }}
                </span>

            @enderror



            <button
                type="submit"
                class="btn btn-dark mt-7 w-full"
            >
                Create account
            </button>

        </form>



        <div
            class="
                mt-6
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

            <div class="flex items-start gap-2">

                <i
                    class="
                        fa-regular
                        fa-envelope
                        mt-0.5
                        text-[#a84f27]
                    "
                ></i>

                <span>
                    Email registrations require verification.
                    Google registrations are verified automatically
                    through Google sign-in.
                </span>

            </div>

        </div>



        <p class="mt-6 text-center text-sm">

            Already registered?

            <a
                class="underline"
                href="{{ route('login') }}"
            >
                Sign in
            </a>

        </p>

    </div>

</div>

@endsection


@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        document
            .querySelectorAll(
                '.js-password-toggle'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const input =
                                document.getElementById(
                                    button.dataset.target
                                );


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

    }
);

</script>

@endpush