<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Verify your SARAI email address
    </title>

</head>


<body
    style="
        margin:0;
        padding:0;
        background:#f5f1eb;
        font-family:Arial,Helvetica,sans-serif;
        color:#1d1d1b;
    "
>


<table
    role="presentation"
    width="100%"
    cellspacing="0"
    cellpadding="0"
    border="0"
    style="
        width:100%;
        background:#f5f1eb;
        margin:0;
        padding:0;
    "
>


    <tr>

        <td
            align="center"
            style="
                padding:34px 16px;
            "
        >


            <table
                role="presentation"
                width="100%"
                cellspacing="0"
                cellpadding="0"
                border="0"
                style="
                    max-width:620px;
                    width:100%;
                    background:#ffffff;
                    border:1px solid #e3ddd4;
                "
            >


                {{-- HEADER --}}
                <tr>

                    <td
                        align="center"
                        style="
                            padding:34px 34px 22px 34px;
                            border-bottom:1px solid #eee8e0;
                        "
                    >


                        <img
                            src="{{ url('/favicon/favicon-192.png') }}"
                            width="54"
                            height="54"
                            alt="SARAI"
                            style="
                                display:block;
                                width:54px;
                                height:54px;
                                border:0;
                                outline:none;
                                text-decoration:none;
                            "
                        >


                        <div
                            style="
                                margin-top:13px;
                                font-size:18px;
                                font-weight:700;
                                letter-spacing:2px;
                                color:#173d32;
                            "
                        >
                            SARAI
                        </div>


                        <div
                            style="
                                margin-top:5px;
                                font-size:11px;
                                letter-spacing:1.4px;
                                text-transform:uppercase;
                                color:#8b8178;
                            "
                        >
                            Authentic Bangladeshi craft & lifestyle
                        </div>


                    </td>

                </tr>



                {{-- MESSAGE --}}
                <tr>

                    <td
                        style="
                            padding:38px 42px 14px 42px;
                        "
                    >


                        <div
                            style="
                                font-size:12px;
                                font-weight:700;
                                letter-spacing:1.8px;
                                text-transform:uppercase;
                                color:#a8542a;
                            "
                        >
                            Email verification
                        </div>


                        <h1
                            style="
                                margin:12px 0 0 0;
                                font-size:31px;
                                line-height:1.2;
                                font-weight:600;
                                color:#171717;
                            "
                        >

                            Welcome to SARAI,
                            {{ $user->name }}.

                        </h1>


                        <p
                            style="
                                margin:18px 0 0 0;
                                font-size:15px;
                                line-height:1.75;
                                color:#5e5953;
                            "
                        >

                            Thank you for creating your SARAI account.

                            Please verify your email address so you can
                            securely access your account, wishlist,
                            order history and customer features.

                        </p>


                    </td>

                </tr>



                {{-- VERIFY BUTTON --}}
                <tr>

                    <td
                        align="center"
                        style="
                            padding:20px 42px 28px 42px;
                        "
                    >


                        <a
                            href="{{ $verificationUrl }}"
                            style="
                                display:inline-block;
                                background:#173d32;
                                color:#ffffff;
                                text-decoration:none;
                                font-size:12px;
                                font-weight:700;
                                letter-spacing:1.2px;
                                text-transform:uppercase;
                                padding:16px 26px;
                                border-radius:3px;
                            "
                        >
                            Verify my email
                        </a>


                    </td>

                </tr>



                {{-- SECURITY INFO --}}
                <tr>

                    <td
                        style="
                            padding:0 42px 34px 42px;
                        "
                    >


                        <div
                            style="
                                background:#faf8f5;
                                border:1px solid #ebe4dc;
                                padding:18px 20px;
                            "
                        >


                            <p
                                style="
                                    margin:0;
                                    font-size:13px;
                                    line-height:1.65;
                                    color:#655e57;
                                "
                            >

                                This verification link expires in
                                approximately

                                <strong style="color:#222222;">

                                    {{ $expiresInMinutes }} minutes

                                </strong>.


                                If you did not create a SARAI account,
                                you can safely ignore this email.

                            </p>


                        </div>


                    </td>

                </tr>



                {{-- FALLBACK URL --}}
                <tr>

                    <td
                        style="
                            padding:0 42px 36px 42px;
                        "
                    >


                        <p
                            style="
                                margin:0;
                                font-size:12px;
                                line-height:1.6;
                                color:#8b8178;
                            "
                        >

                            If the button above does not work,
                            copy and paste this secure URL into
                            your browser:

                        </p>


                        <p
                            style="
                                margin:8px 0 0 0;
                                font-size:11px;
                                line-height:1.55;
                                word-break:break-all;
                                color:#a8542a;
                            "
                        >

                            {{ $verificationUrl }}

                        </p>


                    </td>

                </tr>



                {{-- FOOTER --}}
                <tr>

                    <td
                        align="center"
                        style="
                            padding:22px 32px;
                            background:#111b19;
                            color:#c8d2ce;
                        "
                    >


                        <p
                            style="
                                margin:0;
                                font-size:11px;
                                line-height:1.6;
                            "
                        >

                            © {{ date('Y') }} SARAI.

                            Curated craft, fashion and lifestyle
                            from Bangladesh.

                        </p>


                        <p
                            style="
                                margin:6px 0 0 0;
                                font-size:10px;
                                line-height:1.5;
                                color:#93a39d;
                            "
                        >

                            This is an automated account-security email.
                            Please do not share the verification link.

                        </p>


                    </td>

                </tr>


            </table>


        </td>

    </tr>


</table>


</body>

</html>