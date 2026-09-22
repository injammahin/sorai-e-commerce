@extends('layouts.store')

@section('title', 'Verify Email | SARAI')

@section('content')

<div class="auth-shell">

    <div class="auth-card !max-w-[560px] text-center">

        <div
            class="
                mx-auto
                grid
                h-16
                w-16
                place-items-center
                rounded-full
                bg-[#f2ede6]
                text-[#a84f27]
            "
        >

            <i
                class="
                    fa-regular
                    fa-envelope
                    text-2xl
                "
            ></i>

        </div>


        <p class="eyebrow mt-5">
            One final step
        </p>


        <h1 class="mt-2 text-4xl sm:text-5xl">
            Verify your email
        </h1>


        <p
            class="
                muted
                mx-auto
                mt-4
                max-w-md
                text-sm
                leading-6
            "
        >

            We sent a secure verification link to

            <strong class="text-black">
                {{ auth()->user()->email }}
            </strong>.

            Open the email and click the verification button
            to activate your SARAI account.

        </p>



        <div
            class="
                mt-7
                rounded-sm
                border
                border-[#e5ded5]
                bg-[#faf8f5]
                p-4
                text-left
                text-xs
                leading-5
                text-gray-600
            "
        >

            <div class="flex items-start gap-3">

                <i
                    class="
                        fa-solid
                        fa-circle-info
                        mt-0.5
                        text-[#a84f27]
                    "
                ></i>


                <div>

                    <p class="font-medium text-black">
                        Didn't receive it?
                    </p>

                    <p class="mt-1">
                        Check your spam or promotions folder,
                        then resend a fresh verification link below.
                    </p>

                </div>

            </div>

        </div>



        <form
            action="{{ route('verification.send') }}"
            method="POST"
            class="mt-7"
        >

            @csrf


            <button
                type="submit"
                class="btn btn-dark w-full"
            >

                <i class="fa-regular fa-paper-plane"></i>

                Resend verification email

            </button>

        </form>



        <form
            action="{{ route('logout') }}"
            method="POST"
            class="mt-3"
        >

            @csrf


            <button
                type="submit"
                class="btn btn-outline w-full"
            >
                Use a different account
            </button>

        </form>

    </div>

</div>

@endsection