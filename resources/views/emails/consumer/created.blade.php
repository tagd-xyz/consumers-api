@extends('emails.layout')

@section('title', 'Claim your Tagd')

@section('preview')
You've got an Tagd waiting for you.
@endsection

@section('content')
<!-- START CENTERED WHITE CONTAINER -->
<table role="presentation" class="main">

    <!-- START MAIN CONTENT AREA -->
    <tr>
    <td class="wrapper">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <h2>
                    You've got an item waiting for you.
                    <br>
                    <small>
                        Sign up in portal_url to claim your Tagd.
                    </small>
                </h2>
            </td>
        </tr>
        </table>
    </td>
    </tr>

<!-- END MAIN CONTENT AREA -->
</table>
<!-- END CENTERED WHITE CONTAINER -->
@endsection

@section('footer')
<!-- START FOOTER -->
<div class="footer">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="content-block powered-by">
        Powered by <a href="https://tagd">TagdId</a>.
        </td>
    </tr>
    </table>
</div>
<!-- END FOOTER -->
@endsection